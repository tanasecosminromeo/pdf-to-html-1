<?php

declare(strict_types=1);

namespace Wbrframe\PdfToHtml\PopplerUtils;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Wbrframe\PdfToHtml\Exception\RuntimeException;

/**
 * PdfToHtml.
 */
class Pdf
{
    const DEFAULT_PDFTOHTML_BIN_PATH = '/usr/bin/pdftohtml';

    /**
     * @var string
     */
    private $binPath;

    /**
     * @var string
     */
    private $inputFilePath;

    /**
     * @var string
     */
    private $outputFilePath;

    /**
     * @param string      $inputFilePath
     * @param string|null $outputFolder
     * @param string|null $binPath
     *
     * @throws RuntimeException
     */
    public function __construct(string $inputFilePath, string $outputFolder = null, string $binPath = null)
    {
        if (!$this->commandExists()) {
            throw new RuntimeException('Before use the library, install poppler-utils');
        }

        $this->inputFilePath = $inputFilePath;
        $this->outputFilePath = $this->prepareOutputFilePath($outputFolder);
        $this->binPath = $binPath ?? self::DEFAULT_PDFTOHTML_BIN_PATH;
    }

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    public function getOutputHtmlFilePath(): string
    {
        $command = $this->prepareCommand();
        exec($command);

        $fs = new Filesystem();
        if (!$fs->exists($this->inputFilePath)) {
            throw new RuntimeException('HTML failed to create');
        }

        return $this->outputFilePath;
    }

    /**
     * @return string
     */
    private function prepareCommand(): string
    {
        return \sprintf('%s %s -s -i -noframes %s',
            $this->binPath,
            $this->inputFilePath,
            $this->outputFilePath
        );
    }

    /**
     * @return bool
     */
    private function commandExists(): bool
    {
        return \is_executable(self::DEFAULT_PDFTOHTML_BIN_PATH);
    }

    /**
     * @param string|null $outputFolder
     *
     * @return string
     */
    private function prepareOutputFilePath(string $outputFolder = null): string
    {
        $outputFolder = $outputFolder ?? $this->createOutputFolder();

        return \sprintf('%s/%s.html', $outputFolder, \uniqid('', true));
    }

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    private function createOutputFolder(): string
    {
        $fs = new Filesystem();

        $outputFolder = \sprintf('%s/pdf-to-html', \sys_get_temp_dir());
        try {
            $fs->mkdir($outputFolder);
        } catch (IOException $exception) {
            throw new RuntimeException(\sprintf('Directory %s failed to create', $outputFolder));
        }

        return $outputFolder;
    }
}
