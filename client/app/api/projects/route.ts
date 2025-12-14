import { NextResponse } from 'next/server';
import { query } from '@/lib/db';

export async function GET() {
  try {
    const projects = await query("SELECT * FROM projects ORDER BY id");
    // Convertir les technologies en tableau
    const formattedProjects = projects.map((project: any) => {
      if (project.technologies && typeof project.technologies === 'string') {
        try {
          project.technologies = JSON.parse(project.technologies);
        } catch {
          project.technologies = [];
        }
      }
      return project;
    });
    
    return NextResponse.json(formattedProjects, {
      status: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*',
      },
    });
  } catch (error: any) {
    console.error('Projects API error:', error);
    return NextResponse.json(
      { error: error?.message || 'Database error' },
      { status: 500 }
    );
  }
}

