/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: 'http://localhost/Portfolio/server/api/:path*',
      },
    ];
  },
}

module.exports = nextConfig

