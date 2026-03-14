const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    page.on('console', msg => {
        if (msg.type() === 'error') {
            console.log('BROWSER ERROR:', msg.text());
        }
    });

    page.on('pageerror', err => {
        console.log('PAGE ERROR:', err.toString());
    });

    try {
        await page.goto('http://127.0.0.1:8000/admin/reports', { waitUntil: 'networkidle0' });
        console.log('Successfully loaded page.');
    } catch (e) {
        console.log('NAVIGATION ERROR:', e.message);
    }
    
    await browser.close();
})();
