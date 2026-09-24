export async function fetchJSON(file) {
  const response = await fetch(file);
  if (!response.ok) throw new Error(`Unable to load ${file}`);
  return await response.json();
}
