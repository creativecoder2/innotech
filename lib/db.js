import mongoose from 'mongoose';

const MONGODB_URI = process.env.MONGODB_URI;

let cached = global.mongoose;

if (!cached) {
  cached = global.mongoose = { conn: null, promise: null, lastFailedAt: 0 };
}

const RETRY_COOLDOWN_MS = 15000; // Wait 15s before re-attempting if offline

async function connectToDatabase() {
  if (!MONGODB_URI) {
    return null;
  }

  if (cached.conn) {
    return cached.conn;
  }

  // If recent connection failed, serve instant fallback without waiting for timeout
  const now = Date.now();
  if (cached.lastFailedAt && (now - cached.lastFailedAt < RETRY_COOLDOWN_MS)) {
    return null;
  }

  if (!cached.promise) {
    const opts = {
      bufferCommands: false,
      serverSelectionTimeoutMS: 1000, // 1 second fast timeout
      connectTimeoutMS: 1000,
    };

    cached.promise = mongoose
      .connect(MONGODB_URI, opts)
      .then((mongooseInstance) => {
        cached.lastFailedAt = 0;
        return mongooseInstance;
      })
      .catch((err) => {
        console.warn('MongoDB not reachable (running with fast local fallback):', err.message);
        cached.lastFailedAt = Date.now();
        cached.promise = null;
        return null;
      });
  }

  try {
    cached.conn = await cached.promise;
  } catch (e) {
    cached.lastFailedAt = Date.now();
    cached.promise = null;
    return null;
  }

  return cached.conn;
}

export default connectToDatabase;

