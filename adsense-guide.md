# Google AdSense Implementation Guide

This guide explains where to place your Google AdSense code in your blog application.

## Where to Place Your AdSense Code

The AdSense code has been strategically placed in the following locations:

1. **Header Ad** - Below the navigation bar on all pages
2. **Sidebar Ad** - At the top of the sidebar on all pages
3. **Content Ad** - After the article content on single post pages

## How to Add Your AdSense Code

1. Open the file: `app/Helpers/AdSense_helper.php`
2. Replace `ca-pub-XXXXXXXXXXXXXXXX` with your actual Google AdSense Publisher ID
3. Replace `XXXXXXXXXX` with your actual ad slot IDs for each ad unit

Example:
```php
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456"
    crossorigin="anonymous"></script>
<ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-1234567890123456"
    data-ad-slot="9876543210"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```

## AdSense Auto Ads

If you prefer to use Google AdSense Auto Ads instead of manually placed ads:

1. Get your Auto Ads code from your AdSense account
2. Open `app/Views/frontend/layout/pages-layout.php`
3. Add your Auto Ads code just after the `<head>` tag

Example:
```html
<head>
    <!-- Google AdSense Auto Ads -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456" 
    crossorigin="anonymous"></script>
    
    <!-- Rest of your head content -->
    <meta charset="utf-8">
    ...
</head>
```

## Best Practices

1. Don't place too many ads on a single page to avoid slowing down your website
2. Ensure ads don't interfere with the user experience
3. Follow Google AdSense policies to avoid account suspension
4. Test your website's performance after adding ads to ensure it loads quickly