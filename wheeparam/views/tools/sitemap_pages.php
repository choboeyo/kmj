<?php
ob_start();
echo "<?xml version=\"1.0\" encoding=\"" . config_item('charset') . "\"?".">\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

if ( count($list) > 0 ) {
    foreach ($list as $row) {
        echo "<url>".PHP_EOL;
        echo "<loc>{$row['loc']}</loc>".PHP_EOL;
        echo "<lastmod>{$row['lastmod']}</lastmod>".PHP_EOL;
        echo "<priority>{$row['priority']}</priority>".PHP_EOL;
        echo "<changefreq>{$row['changefreq']}</changefreq>".PHP_EOL;
        echo "</url>\n";
    }
}
echo "</urlset>\n";

$xml = ob_get_clean();

echo $xml;
