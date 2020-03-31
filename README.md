# PDF to HTML PHP Class (only for Linux)

A small wrapper for Linux, which allows you to convert pdf files to html.

## Important Notes

The package is still in development. Currently has the following restrictions:
- only for Linux;
- `pdftohtml` executing with next params: `-s -i -noframes`
- output HTML file creating in `/<system_temp_dir>/pdf-to-html/`. Example: `/tmp/pdf-to-html/5e82b7db72e674.60365088.html`

## Installation

When you are in your active directory apps, you can just run this command to add this package on your app

```
composer req wbrframe/pdf-to-html
```

## Requirements
1. Poppler-Utils (if you are using Ubuntu Distro, just install it from apt )

`sudo apt-get install poppler-utils`

## Usage

Here is the sample.

```php
<?php
// if you are using composer, just use this
include 'vendor/autoload.php';

// initiate
$pdf = new Wbrframe\PdfToHtml\PopplerUtils\Pdf('example.pdf');

// get a path for an output HTML file
$htmlFile = $pdf->getOutputHtmlFilePath();
?>
```

You can change the path to `phptohtml` passing the second argument.

```php
<?php
$pdf = new Wbrframe\PdfToHtml\PopplerUtils\Pdf('example.pdf', '/new/path/pdftohtml');
?>
```
