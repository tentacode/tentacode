import { test, expect } from '@playwright/test';

test('homepage loads with no console errors', async ({ page }) => {
  const errors: string[] = [];
  page.on('console', (msg) => {
    if (msg.type() === 'error') errors.push(msg.text());
  });

  await page.goto('/');
  await expect(page).toHaveTitle(/tentacode\.dev/);
  expect(errors).toHaveLength(0);
});
