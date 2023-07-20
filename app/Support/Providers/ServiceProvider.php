<?php
namespace App\Support\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

abstract class ServiceProvider extends LaravelServiceProvider
{
    /**
     * @var Lista de fornecedores de serviços da unidade para registrar
     */
    protected $providers = [];

    /**
     * @var string Unit Alias  para traduções e exibições
     */
    protected $alias;

    /**
     * @var bool Ativar visualizações carregando no Unity
     */
    protected $hasViews = false;

    /**
     * @var bool Ativar traduções carregando no Unity
     */
    protected $hasTranslations = false;

    /**
     * inicialização exigia o registro de visualizações e traduções
     */
    public function boot()
    {
        // register unity translations.
        $this->registerTranslations();

        // register unity views.
        $this->registerViews();
    }


    public function register()
    {
        // registrar domínios personalizados de unidade.
        $this->registerProviders(collect($this->providers));
    }


    /**
     * Register os ServiceProviders personalizados da unidade.
     *
     * @param Collection $providers
     */
    protected function registerProviders(Collection $providers)
    {
        // loop in de provider to register
        $providers->each(function ($providerClass) {

            // register class in provider
            $this->app->register($providerClass);
        });
    }


    /**
     * Registrar tradução unificadas.
     */
    protected function registerTranslations()
    {
        if ($this->hasTranslations) {
            $this->loadTranslationsFrom(
                $this->unitPath('Resources/Lang'),
                $this->alias
            );
        }
    }


    /**
     * Registrar visões de unidade.
     */
    protected function registerViews()
    {
        if ($this->hasViews) {
            $this->loadViewsFrom(
                $this->unitPath('Resources/Views'),
                $this->alias
            );
        }
    }


    /**
     * Detecta o caminho base da unidade para que os recursos possam ser carregados adequadamente
     * na classe filha
     *
     * @param null $append
     * @return string
     */
    protected function unitPath($append = null): string
    {
        $reflection = new \ReflectionClass($this);
        $realPath = realpath(dirname($reflection->getFileName()) . '/../');
        if (!$append) {
            return $realPath;
        }
        return $realPath . '/' . $append;
    }
}
