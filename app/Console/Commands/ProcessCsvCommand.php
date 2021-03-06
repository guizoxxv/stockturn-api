<?php

namespace App\Console\Commands;

use App\Repositories\ProductRepository;
use App\Repositories\UploadRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProcessCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-csv {uploadId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process CSV file';

    private ProductRepository $productRepository;
    private UploadRepository $uploadRepository;
    private int $lineNumber = 0;
    private int $totalLines;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        UploadRepository $uploadRepository,
    ) {
        parent::__construct();

        $this->productRepository = $productRepository;
        $this->uploadRepository = $uploadRepository;
    }

    public function handle()
    {
        $this->info('ProcessCsv job started');

        try {
            $uploadId = (int)$this->argument('uploadId');

            $upload = $this->uploadRepository->find($uploadId);

            $filePath = storage_path('app/' . $upload->path);

            $this->totalLines = $this->getTotalLines($filePath);

            $handle = fopen($filePath, 'r');

            $this->processFile($handle);

            $this->uploadRepository->update(
                [
                    'status' => 'PROCESSED',
                    'message' => null,
                ],
                $uploadId,
            );

            $this->info('ProcessCsv job finished');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            $this->error("ProcessCsv job failed - {$errorMessage}");

            $this->uploadRepository->update(
                [
                    'status' => 'ERROR',
                    'message' => $e->getMessage(),
                ],
                $uploadId,
            );

            throw $e;
        }
    }

    private function getTotalLines(string $filePath)
    {
        $file = new \SplFileObject($filePath, 'r');

        $file->seek(PHP_INT_MAX);

        $lastLine = $file->key();

        return $lastLine + 1;
    }

    private function processFile($handle)
    {
        DB::transaction(function () use ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);

                if ($this->lineNumber === 0) {
                    $this->validateHeader($line);

                    $this->lineNumber++;

                    continue;
                }

                $this->info("Processing line {$this->lineNumber} of {$this->totalLines}.");

                $formattedLine = $this->formatLine($line);

                $this->validateLine($formattedLine);

                $this->productRepository->upsert($formattedLine);

                $this->lineNumber++;
            }
        });
    }

    private function validateHeader(string $line): void {
        if ($line !== 'id,name,price,stock') {
            throw new \Exception('Invalid header');
        }
    }

    private function formatLine(string $line): array {
        $formattedLine = [];
        $line = explode(',', $line);

        if (@$line[0]) {
            $formattedLine['id'] = $line[0];
        }

        if (@$line[1]) {
            $formattedLine['name'] = $line[1];
        }

        if (is_numeric($line[2])) {
            $formattedLine['price'] = (float) $line[2];
        }

        if (is_numeric($line[3])) {
            $formattedLine['price'] = (int) $line[3];
        }

        return $formattedLine;
    }

    private function validateLine(array $data): void {
        $validator = Validator::make($data, [
            'id' => 'nullable|integer|exists:products',
            'name' => 'required_without:id|string|max:255',
            'price' => 'required_without:id|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode($validator->errors()->all()));
        }
    }
}
