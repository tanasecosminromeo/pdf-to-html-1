<?php

declare(strict_types=1);

namespace Wbrframe\PdfToHtml;

/**
 * PdfToHtml.
 */
class Pdf
{
    public const PDFTOHTML_BIN_PATH = '/usr/bin/pdftohtml';

    /**
     * @var string
     */
    private $inputFilePath;

    /**
     * @var string
     */
    private $outputFilePath;

    /**
     * @param string $inputFilePath
     *
     * @throws \RuntimeException
     */
    public function __construct(string $inputFilePath)
    {
        if (!$this->commandExists()) {
            throw new \RuntimeException('Before use the library, install poppler-utils');
        }

        $this->inputFilePath = $inputFilePath;
        $this->outputFilePath = \sprintf('%s/%s.html', $this->createOutputFolder(), \uniqid('', true));
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getOutputHtmlFilePath(): string
    {
        $command = $this->prepareCommand();
        exec($command);

        if (!\file_exists($this->inputFilePath)) {
            throw new \RuntimeException('HTML failed to create');
        }

        return $this->outputFilePath;
    }

    /**
     * @return string
     */
    private function prepareCommand(): string
    {
        return \sprintf('%s %s -s -i -noframes %s',
            self::PDFTOHTML_BIN_PATH,
            $this->inputFilePath,
            $this->outputFilePath
        );
    }

    /**
     * @return bool
     */
    private function commandExists(): bool
    {
        return \is_executable(self::PDFTOHTML_BIN_PATH);
    }

    /**
     * @return string
     */
    private function createOutputFolder(): string
    {
        $outputFolder = \sprintf('%s/pdf-to-html', \sys_get_temp_dir());
        if (\is_dir($outputFolder)) {
            return $outputFolder;
        }

        if (!mkdir($outputFolder) && !is_dir($outputFolder)) {
            throw new \RuntimeException(\sprintf('Directory %s failed to create', $outputFolder));
        }

        return $outputFolder;
    }
}
