<?php
declare(strict_types=1);
namespace Despark\Cms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class AutosortCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'igni:autosort {model : FQN with reversed slashes for the model: ex: /App/Models/Post} {field : Field from the sortable fields which to be used.}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autosort (gap fill) models with sortable feature.';

    /**
     * Model
     *
     * @var string
     */
    protected $model;

    /**
     * Selected sortable field.
     * @var string
     */
    protected $sortableField;

    /**
     * Model's sortable fields.
     * @var string
     */
    protected $sortableFields;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->model = $this->getModel($this->argument('model'));
        $this->sortableFields = $this->getSortableFields();
        $this->sortableField = $this->getSortableField($this->argument('field'));
        $this->sort();

        $this->line(PHP_EOL);
        $this->info('Sorting for '.$this->sortableField.' in '.get_class($this->model).' completed!');
    }

    /**
     * Swap the slashes.
     *
     * @param  string $model
     * @return string
     */
    private function prepareModel(string $model): string
    {
        return str_replace('/', '\\', $model);
    }


    private function verifyModel(string $model): void
    {
        if (!class_exists($model)) {
            throw new \Exception('Model Class '.$model.' not found.', 1);

        }

        if (!app()->make($model)->isSortable()) {
            throw new \Exception('Model Class '.$model.' is not sortable.', 1);
        }
    }

    /**
     * Get model instance.
     *
     * @param  string $model
     * @return Model
     */
    public function getModel(string $model): Model
    {
        $model = $this->prepareModel($model);
        $this->verifyModel($model);

        return app()->make($model);
    }

    /**
     * Get model's sortable fields.
     *
     * @return array
     */
    private function getSortableFields(): array
    {
        return $this->model->getSortableFieldsKeys();
    }

    /**
     * Verify that field exists in model's sortableFields
     *
     * @param string $field
     */
    private function verifySortableField(string $field): void
    {
        if (!in_array($field, $this->sortableFields)) {
            throw new \Exception('Field '.$field.' does not exist in sortableFields for '.get_class($this->model).' model.', 1);
        }
    }

    /**
     * Get the sortable field
     *
     * @param  string $field
     * @return string
     */
    public function getSortableField(string $field): string
    {
        $this->verifySortableField($field);
        return $field;
    }

    /**
     * Get the data for soring
     *
     * @return Collection
     */
    private function getData(): Collection
    {
        return $this->model->orderBy($this->sortableField, 'asc')->get();
    }

    private function sortData(Collection $data, ProgressBar $progressBar): void
    {
        foreach ($data as $index => $item) {
            $item->{$this->sortableField} = $index;
            $item->save();
            $progressBar->advance();
        }
    }

    /**
     * Sorting wrapper
     */
    public function sort(): void
    {
        $data = $this->getData();
        $progressBar = $this->output->createProgressBar(count($data));
        $this->sortData($data, $progressBar);
        $progressBar->finish();
    }
}
