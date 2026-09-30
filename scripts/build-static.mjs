import { cp, mkdir, rm, stat } from 'node:fs/promises';
import path from 'node:path';

/**
 * Assembles the Vercel static output directory.
 *
 * Vercel needs an output directory when a build step runs. Pointing it at
 * public/ would publish the Laravel front controller (public/index.php) as a
 * downloadable file, so we copy an explicit allowlist of assets instead.
 * public/.htaccess is Apache-only and intentionally left behind.
 */
const STATIC_ENTRIES = ['build', 'images', 'favicon.ico', 'robots.txt'];

const root = process.cwd();
const outputDir = path.join(root, '.vercel-static');

await rm(outputDir, { recursive: true, force: true });
await mkdir(outputDir, { recursive: true });

let copied = 0;

for (const entry of STATIC_ENTRIES) {
  const source = path.join(root, 'public', entry);

  try {
    await stat(source);
  } catch {
    console.warn(`[vercel-static] skipped missing public/${entry}`);
    continue;
  }

  await cp(source, path.join(outputDir, entry), { recursive: true });
  copied += 1;
}

console.log(`[vercel-static] copied ${copied}/${STATIC_ENTRIES.length} entries into .vercel-static`);
