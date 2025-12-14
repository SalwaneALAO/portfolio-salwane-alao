import { NextResponse } from 'next/server';
import { query } from '@/lib/db';

export async function GET() {
  try {
    const skills = await query("SELECT * FROM skills ORDER BY category, name");
    return NextResponse.json(skills, {
      status: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*',
      },
    });
  } catch (error: any) {
    console.error('Skills API error:', error);
    return NextResponse.json(
      { error: error?.message || 'Database error' },
      { status: 500 }
    );
  }
}

