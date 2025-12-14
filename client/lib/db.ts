import mysql from 'mysql2/promise';

// Configuration de la base de données
// En production, utilisez des variables d'environnement
const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASS || 'root',
  database: process.env.DB_NAME || 'portfolio_db',
  charset: 'utf8mb4',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
};

// Créer un pool de connexions
let pool: mysql.Pool | null = null;

export function getDBConnection(): mysql.Pool {
  if (!pool) {
    pool = mysql.createPool(dbConfig);
  }
  return pool;
}

// Fonction helper pour exécuter des requêtes
export async function query(sql: string, params?: any[]): Promise<any> {
  const connection = getDBConnection();
  try {
    const [results] = await connection.execute(sql, params || []);
    return results;
  } catch (error) {
    console.error('Database query error:', error);
    throw error;
  }
}

