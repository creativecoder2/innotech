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

  if (cached.conn && mongoose.connection.readyState === 1) {
    return cached.conn;
  }

  // If recent connection failed, return null immediately without waiting
  const now = Date.now();
  if (cached.lastFailedAt && (now - cached.lastFailedAt < RETRY_COOLDOWN_MS)) {
    return null;
  }

  if (!cached.promise) {
    const opts = {
      bufferCommands: false,
      serverSelectionTimeoutMS: 1500,
      connectTimeoutMS: 1500,
      socketTimeoutMS: 2000,
    };

    const timeoutPromise = new Promise((_, reject) =>
      setTimeout(() => reject(new Error('DB connection timed out (1.5s limit)')), 1500)
    );

    cached.promise = Promise.race([
      mongoose.connect(MONGODB_URI, opts),
      timeoutPromise,
    ])
      .then((mongooseInstance) => {
        cached.lastFailedAt = 0;
        return mongooseInstance;
      })
      .catch((err) => {
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

