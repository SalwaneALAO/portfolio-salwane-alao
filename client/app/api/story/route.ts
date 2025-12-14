import { NextResponse } from 'next/server';
import { query } from '@/lib/db';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function GET() {
  try {
    const story = await query("SELECT * FROM story ORDER BY display_order ASC, year ASC");
    return NextResponse.json(story, {
      status: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*',
      },
    });
  } catch (error: any) {
    console.error('Story API error:', error);
    return NextResponse.json(
      { error: error?.message || 'Database error' },
      { status: 500 }
    );
  }
}

