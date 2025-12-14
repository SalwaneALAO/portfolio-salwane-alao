import { NextResponse } from 'next/server';
import { query } from '@/lib/db';

export async function GET() {
  try {
    const qualities = await query("SELECT * FROM qualities ORDER BY id");
    return NextResponse.json(qualities, {
      status: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*',
      },
    });
  } catch (error: any) {
    console.error('Qualities API error:', error);
    return NextResponse.json(
      { error: error?.message || 'Database error' },
      { status: 500 }
    );
  }
}

