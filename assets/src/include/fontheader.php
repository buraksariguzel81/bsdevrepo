<?php
function getFontHeaderHtml()
{
  return <<<HTML
<!-- Bootstrap Icons -- lang="tr">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Tüm Fontlar -->
<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sevillana&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rubik+Bubbles&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Stalinist+One&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Gloria+Hallelujah&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Agu+Display&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Delicious+Handrawn&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IM+Fell+DW+Pica:ital@0;1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sigmar&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Monomakh&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@200..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<style>
/* Bağlantı stilleri */
a {
  color: black;
  text-decoration: none;
}
a:visited {
  color: gray;
}
a:hover {
  color: red;
  text-decoration: underline;
}
a:active {
  color: orange;
}
ul, ul li {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

/* Font class'ları */
.sevillana-regular { font-family: "Sevillana", serif; }
.righteous-regular { font-family: "Righteous", serif; }
.rubik-bubbles-regular { font-family: "Rubik Bubbles", serif; color: red; }
.stalinist-one-regular { font-family: "Stalinist One", serif; color: orange; }
.gloria-hallelujah-regular { font-family: "Gloria Hallelujah", serif; }
.agu-display { font-family: "Agu Display", serif; }
.bungee-shade-regular { font-family: "Bungee Shade", serif; }
.special-elite-regular { font-family: "Special Elite", serif; font-weight: bold; color: red; }
.kaushan-script-regular { font-family: "Kaushan Script", serif; }
.delicious-handrawn-regular { font-family: "Delicious Handrawn", serif; color: orange; }
.im-fell-dw-pica-regular { font-family: "IM Fell DW Pica", serif; }
.im-fell-dw-pica-regular-italic { font-family: "IM Fell DW Pica", serif; font-style: italic; }
.rowdies-light { font-family: "Rowdies", serif; font-weight: 300; }
.rowdies-regular { font-family: "Rowdies", serif; font-weight: 400; }
.rowdies-bold { font-family: "Rowdies", serif; font-weight: 700; }
.monomakh-regular { font-family: "Monomakh", serif; }
.sigmar-regular { font-family: "Sigmar", sans-serif; }
.big-shoulders-stencil { font-family: "Big Shoulders Stencil", sans-serif; }
.yanone-kaffeesatz { font-family: "Yanone Kaffeesatz", sans-serif; font-weight: 700; font-size: 30px; }
.ibm-plex-mono-thin { font-family: "IBM Plex Mono", monospace; font-weight: 100; }
.ibm-plex-mono-light { font-family: "IBM Plex Mono", monospace; font-weight: 300; }
.ibm-plex-mono-regular { font-family: "IBM Plex Mono", monospace; font-weight: 400; }
.ibm-plex-mono-bold { font-family: "IBM Plex Mono", monospace; font-weight: 700; }
.ibm-plex-mono-thin-italic { font-family: "IBM Plex Mono", monospace; font-weight: 100; font-style: italic; }
.ibm-plex-mono-bold-italic { font-family: "IBM Plex Mono", monospace; font-weight: 700; font-style: italic; }

/* Uygulamalı örnek stiller */
.bg-secondary { font-family: "Kaushan Script", serif; }
.bg-secondary input { font-family: "Delicious Handrawn", serif; }
select { font-family: "Agu Display", serif; }
h5 {
  font-family: "Rowdies", serif;
  text-align: center;
  border-bottom: 2px dashed red;
  font-size: 25px;
}
.musteri-bilgileri > p {
  font-family: "IM Fell DW Pica", serif;
}
</style>
HTML;
}
?>