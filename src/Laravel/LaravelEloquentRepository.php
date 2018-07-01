<?php

namespace Nwidart\Modules\Laravel;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Nwidart\Modules\Collection;
use Illuminate\Container\Container;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Entities\ModuleEntity;

class LaravelEloquentRepository implements RepositoryInterface
{
    /**
     * @var ModuleEntity
     */
    private $moduleEntity;
    /**
     * @var Container
     */
    private $app;

    public function __construct(Container $app, ModuleEntity $moduleEntity)
    {
        $this->app = $app;
        $this->moduleEntity = $moduleEntity;
    }

    /**
     * Get all modules.
     * @return EloquentCollection
     */
    public function all()
    {
        return $this->moduleEntity->get();
    }

    /**
     * Get cached modules.
     * @return array
     */
    public function getCached()
    {
        return $this->app['cache']->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }

    /**
     * Scan & get all available modules.
     * @return array
     */
    public function scan()
    {
        return $this->toCollection()->toArray();
    }

    /**
     * Get modules as modules collection instance.
     * @return \Nwidart\Modules\Collection
     */
    public function toCollection()
    {
        return $this->convertToCollection($this->all());
    }

    protected function createModule(...$args)
    {
        return new Module(...$args);
    }

    /**
     * Get scanned paths.
     * @return array
     */
    public function getScanPaths()
    {
    }

    /**
     * Get list of enabled modules.
     * @return mixed
     */
    public function allEnabled(): array
    {
        $results = $this->moduleEntity->newQuery()->where('is_active', 1)->get();

        return $this->convertToCollection($results)->toArray();
    }

    /**
     * Get list of disabled modules.
     * @return mixed
     */
    public function allDisabled()
    {
        // TODO: Implement allDisabled() method.
    }

    /**
     * Get count from all modules.
     * @return int
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Get all ordered modules.
     * @param string $direction
     * @return mixed
     */
    public function getOrdered($direction = 'asc')
    {
        // TODO: Implement getOrdered() method.
    }

    /**
     * Get modules by the given status.
     * @param int $status
     * @return array
     */
    public function getByStatus($status): array
    {
        // TODO: Implement getByStatus() method.
    }

    /**
     * Find a specific module.
     * @param $name
     * @return mixed
     */
    public function find($name)
    {
        // TODO: Implement find() method.
    }

    /**
     * Find a specific module. If there return that, otherwise throw exception.
     * @param $name
     * @return mixed
     */
    public function findOrFail($name)
    {
        // TODO: Implement findOrFail() method.
    }

    public function getModulePath($moduleName)
    {
        // TODO: Implement getModulePath() method.
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        // TODO: Implement getFiles() method.
    }

    public function config($key, $default = null)
    {
        return $this->app['config']->get('modules.' . $key, $default);
    }

    private function convertToCollection(EloquentCollection $eloquentCollection): Collection
    {
        $collection = new Collection();
        $eloquentCollection->map(function ($module) use ($collection) {
            $collection->push($this->createModule($this->app, $module->name, $module->path));
        });
        return $collection;
    }
}
