<?php
$fonts = [
    "Alba",
    "Argor_Biw_Scaqh",
    "BlackOpsOne",
    "Brolink",
    "SquimFont",
    "UnicornPop",
    "Ethnocentric",
    "Langdon",
    "LeviBrush",
    "Moonstar",
    "Nabla",
    "PottaOne",
    "Righteous",
    "Skranji",
    "Turkish_Participants"
];

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Font Preview</title>";

// Otomatik @font-face CSS oluşturma
echo "<style>";
foreach ($fonts as $font) {
    $woff2 = "https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/font/$font/$font.woff2";
    $woff  = "https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/font/$font/$font.woff";
    echo "@font-face { font-family: '$font'; src: url('$woff2') format('woff2'), url('$woff') format('woff'); font-weight: normal; font-style: normal; }";
}
echo "</style>";

echo "</head><body>";
echo "<h2>Font Class Preview</h2>";
echo "<ul>";

foreach ($fonts as $font) {
    echo "<li style='margin-bottom:10px;'>";
    echo "<div style='font-family: \"$font\", sans-serif; font-size: 24px;'>$font — The quick brown fox jumps over the lazy dog</div>";
    echo "<code>.$font</code>";
    echo "</li>";
}

echo "</ul>";
echo "</body></html>";
?>
