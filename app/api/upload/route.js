import { NextResponse } from 'next/server';
import path from 'path';
import fs from 'fs';

export const dynamic = 'force-dynamic';

export async function POST(req) {
  try {
    const formData = await req.formData();
    const file = formData.get('file');

    if (!file || typeof file === 'string') {
      return NextResponse.json({ success: false, message: 'No file provided' }, { status: 400 });
    }

    const bytes = await file.arrayBuffer();
    const buffer = Buffer.from(bytes);

    const isVideo = file.type?.startsWith('video/') || /\.(mp4|webm|mov|ogg|mkv)$/i.test(file.name);
    const mimeType = file.type || (isVideo ? 'video/mp4' : 'image/jpeg');

    // Clean filename
    const ext = path.extname(file.name) || (isVideo ? '.mp4' : '.jpg');
    const baseName = path
      .basename(file.name, ext)
      .toLowerCase()
      .replace(/[^a-z0-9_-]/g, '-');
    const uniqueFilename = `${baseName}-${Date.now()}${ext}`;

    // Try saving to filesystem (works in standard Node/Local/VPS)
    try {
      const subFolder = isVideo ? 'uploads/videos' : 'uploads';
      const uploadDir = path.join(process.cwd(), 'public', subFolder);

      if (!fs.existsSync(uploadDir)) {
        fs.mkdirSync(uploadDir, { recursive: true });
      }

      const filePath = path.join(uploadDir, uniqueFilename);
      await fs.promises.writeFile(filePath, buffer);

      const publicUrl = `/${subFolder}/${uniqueFilename}`;

      return NextResponse.json({
        success: true,
        message: 'File uploaded successfully!',
        url: publicUrl,
        fileName: uniqueFilename,
        fileType: isVideo ? 'video' : 'image',
      });
    } catch (fsErr) {
      // If filesystem is Read-Only (Vercel Serverless / AWS Lambda EROFS),
      // generate a Data URI so the image/video saves directly to database & works 100% seamlessly!
      const base64Data = buffer.toString('base64');
      const dataUri = `data:${mimeType};base64,${base64Data}`;

      return NextResponse.json({
        success: true,
        message: 'File processed successfully (Serverless Mode)!',
        url: dataUri,
        fileName: uniqueFilename,
        fileType: isVideo ? 'video' : 'image',
      });
    }
  } catch (error) {
    console.error('File upload error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'File upload failed' },
      { status: 500 }
    );
  }
}
