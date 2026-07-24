export function env(key, fallback) {
  return window.__ENV__?.[key] ?? import.meta.env[key] ?? fallback;
}
