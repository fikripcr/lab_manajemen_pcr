<?php
/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

/**
 * A helper file for Laravel, to provide autocomplete information to your IDE
 * Generated for Laravel 12.46.0.
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */
namespace Illuminate\Support\Facades {
    /**
     * @see \Illuminate\Foundation\Vite
     */
    class Vite {
        /**
         * Get the preloaded assets.
         *
         * @return array
         * @static
         */
        public static function preloadedAssets()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->preloadedAssets();
        }

        /**
         * Get the Content Security Policy nonce applied to all generated tags.
         *
         * @return string|null
         * @static
         */
        public static function cspNonce()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->cspNonce();
        }

        /**
         * Generate or set a Content Security Policy nonce to apply to all generated tags.
         *
         * @param string|null $nonce
         * @return string
         * @static
         */
        public static function useCspNonce($nonce = null)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useCspNonce($nonce);
        }

        /**
         * Use the given key to detect integrity hashes in the manifest.
         *
         * @param string|false $key
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useIntegrityKey($key)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useIntegrityKey($key);
        }

        /**
         * Set the Vite entry points.
         *
         * @param array $entryPoints
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function withEntryPoints($entryPoints)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->withEntryPoints($entryPoints);
        }

        /**
         * Merge additional Vite entry points with the current set.
         *
         * @param array $entryPoints
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function mergeEntryPoints($entryPoints)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->mergeEntryPoints($entryPoints);
        }

        /**
         * Set the filename for the manifest file.
         *
         * @param string $filename
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useManifestFilename($filename)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useManifestFilename($filename);
        }

        /**
         * Resolve asset paths using the provided resolver.
         *
         * @param callable|null $resolver
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function createAssetPathsUsing($resolver)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->createAssetPathsUsing($resolver);
        }

        /**
         * Get the Vite "hot" file path.
         *
         * @return string
         * @static
         */
        public static function hotFile()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->hotFile();
        }

        /**
         * Set the Vite "hot" file path.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useHotFile($path)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useHotFile($path);
        }

        /**
         * Set the Vite build directory.
         *
         * @param string $path
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useBuildDirectory($path)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useBuildDirectory($path);
        }

        /**
         * Use the given callback to resolve attributes for script tags.
         *
         * @param (callable(string, string, ?array, ?array): array)|array $attributes
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useScriptTagAttributes($attributes)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useScriptTagAttributes($attributes);
        }

        /**
         * Use the given callback to resolve attributes for style tags.
         *
         * @param (callable(string, string, ?array, ?array): array)|array $attributes
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useStyleTagAttributes($attributes)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useStyleTagAttributes($attributes);
        }

        /**
         * Use the given callback to resolve attributes for preload tags.
         *
         * @param (callable(string, string, ?array, ?array): (array|false))|array|false $attributes
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function usePreloadTagAttributes($attributes)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->usePreloadTagAttributes($attributes);
        }

        /**
         * Eagerly prefetch assets.
         *
         * @param int|null $concurrency
         * @param string $event
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function prefetch($concurrency = null, $event = 'load')
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->prefetch($concurrency, $event);
        }

        /**
         * Use the "waterfall" prefetching strategy.
         *
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useWaterfallPrefetching($concurrency = null)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useWaterfallPrefetching($concurrency);
        }

        /**
         * Use the "aggressive" prefetching strategy.
         *
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function useAggressivePrefetching()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->useAggressivePrefetching();
        }

        /**
         * Set the prefetching strategy.
         *
         * @param 'waterfall'|'aggressive'|null $strategy
         * @param array $config
         * @return \Illuminate\Foundation\Vite
         * @static
         */
        public static function usePrefetchStrategy($strategy, $config = [])
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->usePrefetchStrategy($strategy, $config);
        }

        /**
         * Generate React refresh runtime script.
         *
         * @return \Illuminate\Support\HtmlString|void
         * @static
         */
        public static function reactRefresh()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->reactRefresh();
        }

        /**
         * Get the URL for an asset.
         *
         * @param string $asset
         * @param string|null $buildDirectory
         * @return string
         * @static
         */
        public static function asset($asset, $buildDirectory = null)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->asset($asset, $buildDirectory);
        }

        /**
         * Get the content of a given asset.
         *
         * @param string $asset
         * @param string|null $buildDirectory
         * @return string
         * @throws \Illuminate\Foundation\ViteException
         * @static
         */
        public static function content($asset, $buildDirectory = null)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->content($asset, $buildDirectory);
        }

        /**
         * Get a unique hash representing the current manifest, or null if there is no manifest.
         *
         * @param string|null $buildDirectory
         * @return string|null
         * @static
         */
        public static function manifestHash($buildDirectory = null)
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->manifestHash($buildDirectory);
        }

        /**
         * Determine if the HMR server is running.
         *
         * @return bool
         * @static
         */
        public static function isRunningHot()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->isRunningHot();
        }

        /**
         * Get the Vite tag content as a string of HTML.
         *
         * @return string
         * @static
         */
        public static function toHtml()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            return $instance->toHtml();
        }

        /**
         * Flush state.
         *
         * @return void
         * @static
         */
        public static function flush()
        {
            /** @var \Illuminate\Foundation\Vite $instance */
            $instance->flush();
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Foundation\Vite::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void
         * @throws \ReflectionException
         * @static
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Foundation\Vite::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Foundation\Vite::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void
         * @static
         */
        public static function flushMacros()
        {
            \Illuminate\Foundation\Vite::flushMacros();
        }

            }
    /**
     * @see \Illuminate\Database\DatabaseManager
     */
    class DB {
        /**
         * Get a database connection instance.
         *
         * @param \UnitEnum|string|null $name
         * @return \Illuminate\Database\Connection
         * @static
         */
        public static function connection($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->connection($name);
        }

        /**
         * Build a database connection instance from the given configuration.
         *
         * @param array $config
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function build($config)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->build($config);
        }

        /**
         * Calculate the dynamic connection name for an on-demand connection based on its configuration.
         *
         * @param array $config
         * @return string
         * @static
         */
        public static function calculateDynamicConnectionName($config)
        {
            return \Illuminate\Database\DatabaseManager::calculateDynamicConnectionName($config);
        }

        /**
         * Get a database connection instance from the given configuration.
         *
         * @param \UnitEnum|string $name
         * @param array $config
         * @param bool $force
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function connectUsing($name, $config, $force = false)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->connectUsing($name, $config, $force);
        }

        /**
         * Disconnect from the given database and remove from local cache.
         *
         * @param \UnitEnum|string|null $name
         * @return void
         * @static
         */
        public static function purge($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->purge($name);
        }

        /**
         * Disconnect from the given database.
         *
         * @param \UnitEnum|string|null $name
         * @return void
         * @static
         */
        public static function disconnect($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->disconnect($name);
        }

        /**
         * Reconnect to the given database.
         *
         * @param \UnitEnum|string|null $name
         * @return \Illuminate\Database\Connection
         * @static
         */
        public static function reconnect($name = null)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->reconnect($name);
        }

        /**
         * Set the default database connection for the callback execution.
         *
         * @param \UnitEnum|string $name
         * @param callable $callback
         * @return mixed
         * @static
         */
        public static function usingConnection($name, $callback)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->usingConnection($name, $callback);
        }

        /**
         * Get the default connection name.
         *
         * @return string
         * @static
         */
        public static function getDefaultConnection()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->getDefaultConnection();
        }

        /**
         * Set the default connection name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultConnection($name)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->setDefaultConnection($name);
        }

        /**
         * Get all of the supported drivers.
         *
         * @return string[]
         * @static
         */
        public static function supportedDrivers()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->supportedDrivers();
        }

        /**
         * Get all of the drivers that are actually available.
         *
         * @return string[]
         * @static
         */
        public static function availableDrivers()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->availableDrivers();
        }

        /**
         * Register an extension connection resolver.
         *
         * @param string $name
         * @param callable $resolver
         * @return void
         * @static
         */
        public static function extend($name, $resolver)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->extend($name, $resolver);
        }

        /**
         * Remove an extension connection resolver.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function forgetExtension($name)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->forgetExtension($name);
        }

        /**
         * Return all of the created connections.
         *
         * @return array<string, \Illuminate\Database\Connection>
         * @static
         */
        public static function getConnections()
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->getConnections();
        }

        /**
         * Set the database reconnector callback.
         *
         * @param callable $reconnector
         * @return void
         * @static
         */
        public static function setReconnector($reconnector)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            $instance->setReconnector($reconnector);
        }

        /**
         * Set the application instance used by the manager.
         *
         * @param \Illuminate\Contracts\Foundation\Application $app
         * @return \Illuminate\Database\DatabaseManager
         * @static
         */
        public static function setApplication($app)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->setApplication($app);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Database\DatabaseManager::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void
         * @throws \ReflectionException
         * @static
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Database\DatabaseManager::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Database\DatabaseManager::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void
         * @static
         */
        public static function flushMacros()
        {
            \Illuminate\Database\DatabaseManager::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed
         * @throws \BadMethodCallException
         * @static
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Illuminate\Database\DatabaseManager $instance */
            return $instance->macroCall($method, $parameters);
        }

        /**
         * Get a human-readable name for the given connection driver.
         *
         * @return string
         * @static
         */
        public static function getDriverTitle()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getDriverTitle();
        }

        /**
         * Run an insert statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @param string|null $sequence
         * @return bool
         * @static
         */
        public static function insert($query, $bindings = [], $sequence = null)
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->insert($query, $bindings, $sequence);
        }

        /**
         * Get the connection's last insert ID.
         *
         * @return string|int|null
         * @static
         */
        public static function getLastInsertId()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getLastInsertId();
        }

        /**
         * Determine if the connected database is a MariaDB database.
         *
         * @return bool
         * @static
         */
        public static function isMaria()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->isMaria();
        }

        /**
         * Get the server version for the connection.
         *
         * @return string
         * @static
         */
        public static function getServerVersion()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getServerVersion();
        }

        /**
         * Get a schema builder instance for the connection.
         *
         * @return \Illuminate\Database\Schema\MySqlBuilder
         * @static
         */
        public static function getSchemaBuilder()
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getSchemaBuilder();
        }

        /**
         * Get the schema state for the connection.
         *
         * @param \Illuminate\Filesystem\Filesystem|null $files
         * @param callable|null $processFactory
         * @return \Illuminate\Database\Schema\MySqlSchemaState
         * @static
         */
        public static function getSchemaState($files = null, $processFactory = null)
        {
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getSchemaState($files, $processFactory);
        }

        /**
         * Set the query grammar to the default implementation.
         *
         * @return void
         * @static
         */
        public static function useDefaultQueryGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->useDefaultQueryGrammar();
        }

        /**
         * Set the schema grammar to the default implementation.
         *
         * @return void
         * @static
         */
        public static function useDefaultSchemaGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->useDefaultSchemaGrammar();
        }

        /**
         * Set the query post processor to the default implementation.
         *
         * @return void
         * @static
         */
        public static function useDefaultPostProcessor()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->useDefaultPostProcessor();
        }

        /**
         * Begin a fluent query against a database table.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Contracts\Database\Query\Expression|\UnitEnum|string $table
         * @param string|null $as
         * @return \Illuminate\Database\Query\Builder
         * @static
         */
        public static function table($table, $as = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->table($table, $as);
        }

        /**
         * Get a new query builder instance.
         *
         * @return \Illuminate\Database\Query\Builder
         * @static
         */
        public static function query()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->query();
        }

        /**
         * Run a select statement and return a single result.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return mixed
         * @static
         */
        public static function selectOne($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->selectOne($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement and return the first column of the first row.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return mixed
         * @throws \Illuminate\Database\MultipleColumnsSelectedException
         * @static
         */
        public static function scalar($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->scalar($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return array
         * @static
         */
        public static function selectFromWriteConnection($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->selectFromWriteConnection($query, $bindings);
        }

        /**
         * Run a select statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return array
         * @static
         */
        public static function select($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->select($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement against the database and returns all of the result sets.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return array
         * @static
         */
        public static function selectResultSets($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->selectResultSets($query, $bindings, $useReadPdo);
        }

        /**
         * Run a select statement against the database and returns a generator.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return \Generator<int, \stdClass>
         * @static
         */
        public static function cursor($query, $bindings = [], $useReadPdo = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->cursor($query, $bindings, $useReadPdo);
        }

        /**
         * Run an update statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return int
         * @static
         */
        public static function update($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->update($query, $bindings);
        }

        /**
         * Run a delete statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return int
         * @static
         */
        public static function delete($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->delete($query, $bindings);
        }

        /**
         * Execute an SQL statement and return the boolean result.
         *
         * @param string $query
         * @param array $bindings
         * @return bool
         * @static
         */
        public static function statement($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->statement($query, $bindings);
        }

        /**
         * Run an SQL statement and get the number of rows affected.
         *
         * @param string $query
         * @param array $bindings
         * @return int
         * @static
         */
        public static function affectingStatement($query, $bindings = [])
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->affectingStatement($query, $bindings);
        }

        /**
         * Run a raw, unprepared query against the PDO connection.
         *
         * @param string $query
         * @return bool
         * @static
         */
        public static function unprepared($query)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->unprepared($query);
        }

        /**
         * Get the number of open connections for the database.
         *
         * @return int|null
         * @static
         */
        public static function threadCount()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->threadCount();
        }

        /**
         * Execute the given callback in "dry run" mode.
         *
         * @param (\Closure(\Illuminate\Database\Connection): mixed) $callback
         * @return \Illuminate\Database\array{query: string, bindings: array, time: float|null}[]
         * @static
         */
        public static function pretend($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->pretend($callback);
        }

        /**
         * Execute the given callback without "pretending".
         *
         * @param \Closure $callback
         * @return mixed
         * @static
         */
        public static function withoutPretending($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->withoutPretending($callback);
        }

        /**
         * Bind values to their parameters in the given statement.
         *
         * @param \PDOStatement $statement
         * @param array $bindings
         * @return void
         * @static
         */
        public static function bindValues($statement, $bindings)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->bindValues($statement, $bindings);
        }

        /**
         * Prepare the query bindings for execution.
         *
         * @param array $bindings
         * @return array
         * @static
         */
        public static function prepareBindings($bindings)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->prepareBindings($bindings);
        }

        /**
         * Log a query in the connection's query log.
         *
         * @param string $query
         * @param array $bindings
         * @param float|null $time
         * @return void
         * @static
         */
        public static function logQuery($query, $bindings, $time = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->logQuery($query, $bindings, $time);
        }

        /**
         * Register a callback to be invoked when the connection queries for longer than a given amount of time.
         *
         * @param \DateTimeInterface|\Carbon\CarbonInterval|float|int $threshold
         * @param (callable(\Illuminate\Database\Connection, \Illuminate\Database\Events\QueryExecuted): mixed) $handler
         * @return void
         * @static
         */
        public static function whenQueryingForLongerThan($threshold, $handler)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->whenQueryingForLongerThan($threshold, $handler);
        }

        /**
         * Allow all the query duration handlers to run again, even if they have already run.
         *
         * @return void
         * @static
         */
        public static function allowQueryDurationHandlersToRunAgain()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->allowQueryDurationHandlersToRunAgain();
        }

        /**
         * Get the duration of all run queries in milliseconds.
         *
         * @return float
         * @static
         */
        public static function totalQueryDuration()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->totalQueryDuration();
        }

        /**
         * Reset the duration of all run queries.
         *
         * @return void
         * @static
         */
        public static function resetTotalQueryDuration()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->resetTotalQueryDuration();
        }

        /**
         * Reconnect to the database if a PDO connection is missing.
         *
         * @return void
         * @static
         */
        public static function reconnectIfMissingConnection()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->reconnectIfMissingConnection();
        }

        /**
         * Register a hook to be run just before a database transaction is started.
         *
         * @param \Closure $callback
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function beforeStartingTransaction($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->beforeStartingTransaction($callback);
        }

        /**
         * Register a hook to be run just before a database query is executed.
         *
         * @param \Closure $callback
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function beforeExecuting($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->beforeExecuting($callback);
        }

        /**
         * Register a database query listener with the connection.
         *
         * @param \Closure(\Illuminate\Database\Events\QueryExecuted) $callback
         * @return void
         * @static
         */
        public static function listen($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->listen($callback);
        }

        /**
         * Get a new raw query expression.
         *
         * @param mixed $value
         * @return \Illuminate\Contracts\Database\Query\Expression
         * @static
         */
        public static function raw($value)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->raw($value);
        }

        /**
         * Escape a value for safe SQL embedding.
         *
         * @param string|float|int|bool|null $value
         * @param bool $binary
         * @return string
         * @throws \RuntimeException
         * @static
         */
        public static function escape($value, $binary = false)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->escape($value, $binary);
        }

        /**
         * Determine if the database connection has modified any database records.
         *
         * @return bool
         * @static
         */
        public static function hasModifiedRecords()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->hasModifiedRecords();
        }

        /**
         * Indicate if any records have been modified.
         *
         * @param bool $value
         * @return void
         * @static
         */
        public static function recordsHaveBeenModified($value = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->recordsHaveBeenModified($value);
        }

        /**
         * Set the record modification state.
         *
         * @param bool $value
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setRecordModificationState($value)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setRecordModificationState($value);
        }

        /**
         * Reset the record modification state.
         *
         * @return void
         * @static
         */
        public static function forgetRecordModificationState()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->forgetRecordModificationState();
        }

        /**
         * Indicate that the connection should use the write PDO connection for reads.
         *
         * @param bool $value
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function useWriteConnectionWhenReading($value = true)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->useWriteConnectionWhenReading($value);
        }

        /**
         * Get the current PDO connection.
         *
         * @return \PDO
         * @static
         */
        public static function getPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getPdo();
        }

        /**
         * Get the current PDO connection parameter without executing any reconnect logic.
         *
         * @return \PDO|\Closure|null
         * @static
         */
        public static function getRawPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getRawPdo();
        }

        /**
         * Get the current PDO connection used for reading.
         *
         * @return \PDO
         * @static
         */
        public static function getReadPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getReadPdo();
        }

        /**
         * Get the current read PDO connection parameter without executing any reconnect logic.
         *
         * @return \PDO|\Closure|null
         * @static
         */
        public static function getRawReadPdo()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getRawReadPdo();
        }

        /**
         * Set the PDO connection.
         *
         * @param \PDO|\Closure|null $pdo
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setPdo($pdo)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setPdo($pdo);
        }

        /**
         * Set the PDO connection used for reading.
         *
         * @param \PDO|\Closure|null $pdo
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setReadPdo($pdo)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setReadPdo($pdo);
        }

        /**
         * Get the database connection name.
         *
         * @return string|null
         * @static
         */
        public static function getName()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getName();
        }

        /**
         * Get the database connection with its read / write type.
         *
         * @return string|null
         * @static
         */
        public static function getNameWithReadWriteType()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getNameWithReadWriteType();
        }

        /**
         * Get an option from the configuration options.
         *
         * @param string|null $option
         * @return mixed
         * @static
         */
        public static function getConfig($option = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getConfig($option);
        }

        /**
         * Get the PDO driver name.
         *
         * @return string
         * @static
         */
        public static function getDriverName()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getDriverName();
        }

        /**
         * Get the query grammar used by the connection.
         *
         * @return \Illuminate\Database\Query\Grammars\Grammar
         * @static
         */
        public static function getQueryGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getQueryGrammar();
        }

        /**
         * Set the query grammar used by the connection.
         *
         * @param \Illuminate\Database\Query\Grammars\Grammar $grammar
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setQueryGrammar($grammar)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setQueryGrammar($grammar);
        }

        /**
         * Get the schema grammar used by the connection.
         *
         * @return \Illuminate\Database\Schema\Grammars\Grammar
         * @static
         */
        public static function getSchemaGrammar()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getSchemaGrammar();
        }

        /**
         * Set the schema grammar used by the connection.
         *
         * @param \Illuminate\Database\Schema\Grammars\Grammar $grammar
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setSchemaGrammar($grammar)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setSchemaGrammar($grammar);
        }

        /**
         * Get the query post processor used by the connection.
         *
         * @return \Illuminate\Database\Query\Processors\Processor
         * @static
         */
        public static function getPostProcessor()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getPostProcessor();
        }

        /**
         * Set the query post processor used by the connection.
         *
         * @param \Illuminate\Database\Query\Processors\Processor $processor
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setPostProcessor($processor)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setPostProcessor($processor);
        }

        /**
         * Get the event dispatcher used by the connection.
         *
         * @return \Illuminate\Contracts\Events\Dispatcher|null
         * @static
         */
        public static function getEventDispatcher()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getEventDispatcher();
        }

        /**
         * Set the event dispatcher instance on the connection.
         *
         * @param \Illuminate\Contracts\Events\Dispatcher $events
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setEventDispatcher($events)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setEventDispatcher($events);
        }

        /**
         * Unset the event dispatcher for this connection.
         *
         * @return void
         * @static
         */
        public static function unsetEventDispatcher()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->unsetEventDispatcher();
        }

        /**
         * Set the transaction manager instance on the connection.
         *
         * @param \Illuminate\Database\DatabaseTransactionsManager $manager
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setTransactionManager($manager)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setTransactionManager($manager);
        }

        /**
         * Unset the transaction manager for this connection.
         *
         * @return void
         * @static
         */
        public static function unsetTransactionManager()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->unsetTransactionManager();
        }

        /**
         * Determine if the connection is in a "dry run".
         *
         * @return bool
         * @static
         */
        public static function pretending()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->pretending();
        }

        /**
         * Get the connection query log.
         *
         * @return \Illuminate\Database\array{query: string, bindings: array, time: float|null}[]
         * @static
         */
        public static function getQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getQueryLog();
        }

        /**
         * Get the connection query log with embedded bindings.
         *
         * @return array
         * @static
         */
        public static function getRawQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getRawQueryLog();
        }

        /**
         * Clear the query log.
         *
         * @return void
         * @static
         */
        public static function flushQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->flushQueryLog();
        }

        /**
         * Enable the query log on the connection.
         *
         * @return void
         * @static
         */
        public static function enableQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->enableQueryLog();
        }

        /**
         * Disable the query log on the connection.
         *
         * @return void
         * @static
         */
        public static function disableQueryLog()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->disableQueryLog();
        }

        /**
         * Determine whether we're logging queries.
         *
         * @return bool
         * @static
         */
        public static function logging()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->logging();
        }

        /**
         * Get the name of the connected database.
         *
         * @return string
         * @static
         */
        public static function getDatabaseName()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getDatabaseName();
        }

        /**
         * Set the name of the connected database.
         *
         * @param string $database
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setDatabaseName($database)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setDatabaseName($database);
        }

        /**
         * Set the read / write type of the connection.
         *
         * @param string|null $readWriteType
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setReadWriteType($readWriteType)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setReadWriteType($readWriteType);
        }

        /**
         * Get the table prefix for the connection.
         *
         * @return string
         * @static
         */
        public static function getTablePrefix()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->getTablePrefix();
        }

        /**
         * Set the table prefix in use by the connection.
         *
         * @param string $prefix
         * @return \Illuminate\Database\MySqlConnection
         * @static
         */
        public static function setTablePrefix($prefix)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->setTablePrefix($prefix);
        }

        /**
         * Execute the given callback without table prefix.
         *
         * @param \Closure $callback
         * @return mixed
         * @static
         */
        public static function withoutTablePrefix($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->withoutTablePrefix($callback);
        }

        /**
         * Register a connection resolver.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return void
         * @static
         */
        public static function resolverFor($driver, $callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            \Illuminate\Database\MySqlConnection::resolverFor($driver, $callback);
        }

        /**
         * Get the connection resolver for the given driver.
         *
         * @param string $driver
         * @return \Closure|null
         * @static
         */
        public static function getResolver($driver)
        {
            //Method inherited from \Illuminate\Database\Connection 
            return \Illuminate\Database\MySqlConnection::getResolver($driver);
        }

        /**
         * @template TReturn of mixed
         * 
         * Execute a Closure within a transaction.
         * @param (\Closure(static): TReturn) $callback
         * @param int $attempts
         * @return TReturn
         * @throws \Throwable
         * @static
         */
        public static function transaction($callback, $attempts = 1)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->transaction($callback, $attempts);
        }

        /**
         * Start a new database transaction.
         *
         * @return void
         * @throws \Throwable
         * @static
         */
        public static function beginTransaction()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->beginTransaction();
        }

        /**
         * Commit the active database transaction.
         *
         * @return void
         * @throws \Throwable
         * @static
         */
        public static function commit()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->commit();
        }

        /**
         * Rollback the active database transaction.
         *
         * @param int|null $toLevel
         * @return void
         * @throws \Throwable
         * @static
         */
        public static function rollBack($toLevel = null)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->rollBack($toLevel);
        }

        /**
         * Get the number of active transactions.
         *
         * @return int
         * @static
         */
        public static function transactionLevel()
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            return $instance->transactionLevel();
        }

        /**
         * Execute the callback after a transaction commits.
         *
         * @param callable $callback
         * @return void
         * @throws \RuntimeException
         * @static
         */
        public static function afterCommit($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->afterCommit($callback);
        }

        /**
         * Execute the callback after a transaction rolls back.
         *
         * @param callable $callback
         * @return void
         * @throws \RuntimeException
         * @static
         */
        public static function afterRollBack($callback)
        {
            //Method inherited from \Illuminate\Database\Connection 
            /** @var \Illuminate\Database\MySqlConnection $instance */
            $instance->afterRollBack($callback);
        }

            }
    /**
     * @method static void write(string $level, \Illuminate\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Jsonable|\Illuminate\Support\Stringable|array|string $message, array $context = [])
     * @method static \Illuminate\Log\Logger withContext(array $context = [])
     * @method static void listen(\Closure $callback)
     * @method static \Psr\Log\LoggerInterface getLogger()
     * @method static \Illuminate\Contracts\Events\Dispatcher|null getEventDispatcher()
     * @method static void setEventDispatcher(\Illuminate\Contracts\Events\Dispatcher $dispatcher)
     * @method static \Illuminate\Log\Logger|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
     * @method static \Illuminate\Log\Logger|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
     * @see \Illuminate\Log\LogManager
     */
    class Log {
        /**
         * Build an on-demand log channel.
         *
         * @param array $config
         * @return \Psr\Log\LoggerInterface
         * @static
         */
        public static function build($config)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->build($config);
        }

        /**
         * Create a new, on-demand aggregate logger instance.
         *
         * @param array $channels
         * @param string|null $channel
         * @return \Psr\Log\LoggerInterface
         * @static
         */
        public static function stack($channels, $channel = null)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->stack($channels, $channel);
        }

        /**
         * Get a log channel instance.
         *
         * @param string|null $channel
         * @return \Psr\Log\LoggerInterface
         * @static
         */
        public static function channel($channel = null)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->channel($channel);
        }

        /**
         * Get a log driver instance.
         *
         * @param string|null $driver
         * @return \Psr\Log\LoggerInterface
         * @static
         */
        public static function driver($driver = null)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->driver($driver);
        }

        /**
         * Share context across channels and stacks.
         *
         * @param array $context
         * @return \Illuminate\Log\LogManager
         * @static
         */
        public static function shareContext($context)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->shareContext($context);
        }

        /**
         * The context shared across channels and stacks.
         *
         * @return array
         * @static
         */
        public static function sharedContext()
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->sharedContext();
        }

        /**
         * Flush the log context on all currently resolved channels.
         *
         * @param string[]|null $keys
         * @return \Illuminate\Log\LogManager
         * @static
         */
        public static function withoutContext($keys = null)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->withoutContext($keys);
        }

        /**
         * Flush the shared context.
         *
         * @return \Illuminate\Log\LogManager
         * @static
         */
        public static function flushSharedContext()
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->flushSharedContext();
        }

        /**
         * Get the default log driver name.
         *
         * @return string|null
         * @static
         */
        public static function getDefaultDriver()
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->getDefaultDriver();
        }

        /**
         * Set the default log driver name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultDriver($name)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->setDefaultDriver($name);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @param-closure-this $this  $callback
         * @return \Illuminate\Log\LogManager
         * @static
         */
        public static function extend($driver, $callback)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->extend($driver, $callback);
        }

        /**
         * Unset the given channel instance.
         *
         * @param string|null $driver
         * @return void
         * @static
         */
        public static function forgetChannel($driver = null)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->forgetChannel($driver);
        }

        /**
         * Get all of the resolved log channels.
         *
         * @return array
         * @static
         */
        public static function getChannels()
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->getChannels();
        }

        /**
         * System is unusable.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function emergency($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->emergency($message, $context);
        }

        /**
         * Action must be taken immediately.
         * 
         * Example: Entire website down, database unavailable, etc. This should
         * trigger the SMS alerts and wake you up.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function alert($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->alert($message, $context);
        }

        /**
         * Critical conditions.
         * 
         * Example: Application component unavailable, unexpected exception.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function critical($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->critical($message, $context);
        }

        /**
         * Runtime errors that do not require immediate action but should typically
         * be logged and monitored.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function error($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->error($message, $context);
        }

        /**
         * Exceptional occurrences that are not errors.
         * 
         * Example: Use of deprecated APIs, poor use of an API, undesirable things
         * that are not necessarily wrong.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function warning($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->warning($message, $context);
        }

        /**
         * Normal but significant events.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function notice($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->notice($message, $context);
        }

        /**
         * Interesting events.
         * 
         * Example: User logs in, SQL logs.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function info($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->info($message, $context);
        }

        /**
         * Detailed debug information.
         *
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function debug($message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->debug($message, $context);
        }

        /**
         * Logs with an arbitrary level.
         *
         * @param mixed $level
         * @param string|\Stringable $message
         * @param array $context
         * @return void
         * @static
         */
        public static function log($level, $message, $context = [])
        {
            /** @var \Illuminate\Log\LogManager $instance */
            $instance->log($level, $message, $context);
        }

        /**
         * Set the application instance used by the manager.
         *
         * @param \Illuminate\Contracts\Foundation\Application $app
         * @return \Illuminate\Log\LogManager
         * @static
         */
        public static function setApplication($app)
        {
            /** @var \Illuminate\Log\LogManager $instance */
            return $instance->setApplication($app);
        }

            }
    /**
     * @method static \Illuminate\Routing\RouteRegistrar attribute(string $key, mixed $value)
     * @method static \Illuminate\Routing\RouteRegistrar whereAlpha(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereAlphaNumeric(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereNumber(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereUlid(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereUuid(array|string $parameters)
     * @method static \Illuminate\Routing\RouteRegistrar whereIn(array|string $parameters, array $values)
     * @method static \Illuminate\Routing\RouteRegistrar as(string $value)
     * @method static \Illuminate\Routing\RouteRegistrar can(\UnitEnum|string $ability, array|string $models = [])
     * @method static \Illuminate\Routing\RouteRegistrar controller(string $controller)
     * @method static \Illuminate\Routing\RouteRegistrar domain(\BackedEnum|string $value)
     * @method static \Illuminate\Routing\RouteRegistrar middleware(array|string|null $middleware)
     * @method static \Illuminate\Routing\RouteRegistrar missing(\Closure $missing)
     * @method static \Illuminate\Routing\RouteRegistrar name(\BackedEnum|string $value)
     * @method static \Illuminate\Routing\RouteRegistrar namespace(string|null $value)
     * @method static \Illuminate\Routing\RouteRegistrar prefix(string $prefix)
     * @method static \Illuminate\Routing\RouteRegistrar scopeBindings()
     * @method static \Illuminate\Routing\RouteRegistrar where(array $where)
     * @method static \Illuminate\Routing\RouteRegistrar withoutMiddleware(array|string $middleware)
     * @method static \Illuminate\Routing\RouteRegistrar withoutScopedBindings()
     * @see \Illuminate\Routing\Router
     */
    class Route {
        /**
         * Register a new GET route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function get($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->get($uri, $action);
        }

        /**
         * Register a new POST route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function post($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->post($uri, $action);
        }

        /**
         * Register a new PUT route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function put($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->put($uri, $action);
        }

        /**
         * Register a new PATCH route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function patch($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->patch($uri, $action);
        }

        /**
         * Register a new DELETE route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function delete($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->delete($uri, $action);
        }

        /**
         * Register a new OPTIONS route with the router.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function options($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->options($uri, $action);
        }

        /**
         * Register a new route responding to all verbs.
         *
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function any($uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->any($uri, $action);
        }

        /**
         * Register a new fallback route with the router.
         *
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function fallback($action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->fallback($action);
        }

        /**
         * Create a redirect from one URI to another.
         *
         * @param string $uri
         * @param string $destination
         * @param int $status
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function redirect($uri, $destination, $status = 302)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->redirect($uri, $destination, $status);
        }

        /**
         * Create a permanent redirect from one URI to another.
         *
         * @param string $uri
         * @param string $destination
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function permanentRedirect($uri, $destination)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->permanentRedirect($uri, $destination);
        }

        /**
         * Register a new route that returns a view.
         *
         * @param string $uri
         * @param string $view
         * @param array $data
         * @param int|array $status
         * @param array $headers
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function view($uri, $view, $data = [], $status = 200, $headers = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->view($uri, $view, $data, $status, $headers);
        }

        /**
         * Register a new route with the given verbs.
         *
         * @param array|string $methods
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function match($methods, $uri, $action = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->match($methods, $uri, $action);
        }

        /**
         * Register an array of resource controllers.
         *
         * @param array $resources
         * @param array $options
         * @return void
         * @static
         */
        public static function resources($resources, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->resources($resources, $options);
        }

        /**
         * Register an array of resource controllers that can be soft deleted.
         *
         * @param array $resources
         * @param array $options
         * @return void
         * @static
         */
        public static function softDeletableResources($resources, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->softDeletableResources($resources, $options);
        }

        /**
         * Route a resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingResourceRegistration
         * @static
         */
        public static function resource($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->resource($name, $controller, $options);
        }

        /**
         * Register an array of API resource controllers.
         *
         * @param array $resources
         * @param array $options
         * @return void
         * @static
         */
        public static function apiResources($resources, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->apiResources($resources, $options);
        }

        /**
         * Route an API resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingResourceRegistration
         * @static
         */
        public static function apiResource($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->apiResource($name, $controller, $options);
        }

        /**
         * Register an array of singleton resource controllers.
         *
         * @param array $singletons
         * @param array $options
         * @return void
         * @static
         */
        public static function singletons($singletons, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->singletons($singletons, $options);
        }

        /**
         * Route a singleton resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingSingletonResourceRegistration
         * @static
         */
        public static function singleton($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->singleton($name, $controller, $options);
        }

        /**
         * Register an array of API singleton resource controllers.
         *
         * @param array $singletons
         * @param array $options
         * @return void
         * @static
         */
        public static function apiSingletons($singletons, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->apiSingletons($singletons, $options);
        }

        /**
         * Route an API singleton resource to a controller.
         *
         * @param string $name
         * @param string $controller
         * @param array $options
         * @return \Illuminate\Routing\PendingSingletonResourceRegistration
         * @static
         */
        public static function apiSingleton($name, $controller, $options = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->apiSingleton($name, $controller, $options);
        }

        /**
         * Create a route group with shared attributes.
         *
         * @param array $attributes
         * @param \Closure|array|string $routes
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function group($attributes, $routes)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->group($attributes, $routes);
        }

        /**
         * Merge the given array with the last group stack.
         *
         * @param array $new
         * @param bool $prependExistingPrefix
         * @return array
         * @static
         */
        public static function mergeWithLastGroup($new, $prependExistingPrefix = true)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->mergeWithLastGroup($new, $prependExistingPrefix);
        }

        /**
         * Get the prefix from the last group on the stack.
         *
         * @return string
         * @static
         */
        public static function getLastGroupPrefix()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getLastGroupPrefix();
        }

        /**
         * Add a route to the underlying route collection.
         *
         * @param array|string $methods
         * @param string $uri
         * @param array|string|callable|null $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function addRoute($methods, $uri, $action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->addRoute($methods, $uri, $action);
        }

        /**
         * Create a new Route object.
         *
         * @param array|string $methods
         * @param string $uri
         * @param mixed $action
         * @return \Illuminate\Routing\Route
         * @static
         */
        public static function newRoute($methods, $uri, $action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->newRoute($methods, $uri, $action);
        }

        /**
         * Return the response returned by the given route.
         *
         * @param string $name
         * @return \Symfony\Component\HttpFoundation\Response
         * @static
         */
        public static function respondWithRoute($name)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->respondWithRoute($name);
        }

        /**
         * Dispatch the request to the application.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         * @static
         */
        public static function dispatch($request)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->dispatch($request);
        }

        /**
         * Dispatch the request to a route and return the response.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         * @static
         */
        public static function dispatchToRoute($request)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->dispatchToRoute($request);
        }

        /**
         * Gather the middleware for the given route with resolved class names.
         *
         * @param \Illuminate\Routing\Route $route
         * @return array
         * @static
         */
        public static function gatherRouteMiddleware($route)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->gatherRouteMiddleware($route);
        }

        /**
         * Resolve a flat array of middleware classes from the provided array.
         *
         * @param array $middleware
         * @param array $excluded
         * @return array
         * @static
         */
        public static function resolveMiddleware($middleware, $excluded = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->resolveMiddleware($middleware, $excluded);
        }

        /**
         * Create a response instance from the given value.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @param mixed $response
         * @return \Symfony\Component\HttpFoundation\Response
         * @static
         */
        public static function prepareResponse($request, $response)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->prepareResponse($request, $response);
        }

        /**
         * Static version of prepareResponse.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @param mixed $response
         * @return \Symfony\Component\HttpFoundation\Response
         * @static
         */
        public static function toResponse($request, $response)
        {
            return \Illuminate\Routing\Router::toResponse($request, $response);
        }

        /**
         * Substitute the route bindings onto the route.
         *
         * @param \Illuminate\Routing\Route $route
         * @return \Illuminate\Routing\Route
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
         * @throws \Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException
         * @static
         */
        public static function substituteBindings($route)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->substituteBindings($route);
        }

        /**
         * Substitute the implicit route bindings for the given route.
         *
         * @param \Illuminate\Routing\Route $route
         * @return void
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
         * @throws \Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException
         * @static
         */
        public static function substituteImplicitBindings($route)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->substituteImplicitBindings($route);
        }

        /**
         * Register a callback to run after implicit bindings are substituted.
         *
         * @param callable $callback
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function substituteImplicitBindingsUsing($callback)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->substituteImplicitBindingsUsing($callback);
        }

        /**
         * Register a route matched event listener.
         *
         * @param string|callable $callback
         * @return void
         * @static
         */
        public static function matched($callback)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->matched($callback);
        }

        /**
         * Get all of the defined middleware short-hand names.
         *
         * @return array
         * @static
         */
        public static function getMiddleware()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getMiddleware();
        }

        /**
         * Register a short-hand name for a middleware.
         *
         * @param string $name
         * @param string $class
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function aliasMiddleware($name, $class)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->aliasMiddleware($name, $class);
        }

        /**
         * Check if a middlewareGroup with the given name exists.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMiddlewareGroup($name)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->hasMiddlewareGroup($name);
        }

        /**
         * Get all of the defined middleware groups.
         *
         * @return array
         * @static
         */
        public static function getMiddlewareGroups()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getMiddlewareGroups();
        }

        /**
         * Register a group of middleware.
         *
         * @param string $name
         * @param array $middleware
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function middlewareGroup($name, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->middlewareGroup($name, $middleware);
        }

        /**
         * Add a middleware to the beginning of a middleware group.
         * 
         * If the middleware is already in the group, it will not be added again.
         *
         * @param string $group
         * @param string $middleware
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function prependMiddlewareToGroup($group, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->prependMiddlewareToGroup($group, $middleware);
        }

        /**
         * Add a middleware to the end of a middleware group.
         * 
         * If the middleware is already in the group, it will not be added again.
         *
         * @param string $group
         * @param string $middleware
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function pushMiddlewareToGroup($group, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->pushMiddlewareToGroup($group, $middleware);
        }

        /**
         * Remove the given middleware from the specified group.
         *
         * @param string $group
         * @param string $middleware
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function removeMiddlewareFromGroup($group, $middleware)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->removeMiddlewareFromGroup($group, $middleware);
        }

        /**
         * Flush the router's middleware groups.
         *
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function flushMiddlewareGroups()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->flushMiddlewareGroups();
        }

        /**
         * Add a new route parameter binder.
         *
         * @param string $key
         * @param string|callable $binder
         * @return void
         * @static
         */
        public static function bind($key, $binder)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->bind($key, $binder);
        }

        /**
         * Register a model binder for a wildcard.
         *
         * @param string $key
         * @param string $class
         * @param \Closure|null $callback
         * @return void
         * @static
         */
        public static function model($key, $class, $callback = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->model($key, $class, $callback);
        }

        /**
         * Get the binding callback for a given binding.
         *
         * @param string $key
         * @return \Closure|null
         * @static
         */
        public static function getBindingCallback($key)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getBindingCallback($key);
        }

        /**
         * Get the global "where" patterns.
         *
         * @return array
         * @static
         */
        public static function getPatterns()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getPatterns();
        }

        /**
         * Set a global where pattern on all routes.
         *
         * @param string $key
         * @param string $pattern
         * @return void
         * @static
         */
        public static function pattern($key, $pattern)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->pattern($key, $pattern);
        }

        /**
         * Set a group of global where patterns on all routes.
         *
         * @param array $patterns
         * @return void
         * @static
         */
        public static function patterns($patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->patterns($patterns);
        }

        /**
         * Determine if the router currently has a group stack.
         *
         * @return bool
         * @static
         */
        public static function hasGroupStack()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->hasGroupStack();
        }

        /**
         * Get the current group stack for the router.
         *
         * @return array
         * @static
         */
        public static function getGroupStack()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getGroupStack();
        }

        /**
         * Get a route parameter for the current route.
         *
         * @param string $key
         * @param string|null $default
         * @return mixed
         * @static
         */
        public static function input($key, $default = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->input($key, $default);
        }

        /**
         * Get the request currently being dispatched.
         *
         * @return \Illuminate\Http\Request
         * @static
         */
        public static function getCurrentRequest()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getCurrentRequest();
        }

        /**
         * Get the currently dispatched route instance.
         *
         * @return \Illuminate\Routing\Route|null
         * @static
         */
        public static function getCurrentRoute()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getCurrentRoute();
        }

        /**
         * Get the currently dispatched route instance.
         *
         * @return \Illuminate\Routing\Route|null
         * @static
         */
        public static function current()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->current();
        }

        /**
         * Check if a route with the given name exists.
         *
         * @param string|array $name
         * @return bool
         * @static
         */
        public static function has($name)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->has($name);
        }

        /**
         * Get the current route name.
         *
         * @return string|null
         * @static
         */
        public static function currentRouteName()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteName();
        }

        /**
         * Alias for the "currentRouteNamed" method.
         *
         * @param mixed $patterns
         * @return bool
         * @static
         */
        public static function is(...$patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->is(...$patterns);
        }

        /**
         * Determine if the current route matches a pattern.
         *
         * @param mixed $patterns
         * @return bool
         * @static
         */
        public static function currentRouteNamed(...$patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteNamed(...$patterns);
        }

        /**
         * Get the current route action.
         *
         * @return string|null
         * @static
         */
        public static function currentRouteAction()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteAction();
        }

        /**
         * Alias for the "currentRouteUses" method.
         *
         * @param array|string $patterns
         * @return bool
         * @static
         */
        public static function uses(...$patterns)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->uses(...$patterns);
        }

        /**
         * Determine if the current route action matches a given action.
         *
         * @param string $action
         * @return bool
         * @static
         */
        public static function currentRouteUses($action)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->currentRouteUses($action);
        }

        /**
         * Set the unmapped global resource parameters to singular.
         *
         * @param bool $singular
         * @return void
         * @static
         */
        public static function singularResourceParameters($singular = true)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->singularResourceParameters($singular);
        }

        /**
         * Set the global resource parameter mapping.
         *
         * @param array $parameters
         * @return void
         * @static
         */
        public static function resourceParameters($parameters = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->resourceParameters($parameters);
        }

        /**
         * Get or set the verbs used in the resource URIs.
         *
         * @param array $verbs
         * @return array|null
         * @static
         */
        public static function resourceVerbs($verbs = [])
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->resourceVerbs($verbs);
        }

        /**
         * Get the underlying route collection.
         *
         * @return \Illuminate\Routing\RouteCollectionInterface
         * @static
         */
        public static function getRoutes()
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->getRoutes();
        }

        /**
         * Set the route collection instance.
         *
         * @param \Illuminate\Routing\RouteCollection $routes
         * @return void
         * @static
         */
        public static function setRoutes($routes)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->setRoutes($routes);
        }

        /**
         * Set the compiled route collection instance.
         *
         * @param array $routes
         * @return void
         * @static
         */
        public static function setCompiledRoutes($routes)
        {
            /** @var \Illuminate\Routing\Router $instance */
            $instance->setCompiledRoutes($routes);
        }

        /**
         * Remove any duplicate middleware from the given array.
         *
         * @param array $middleware
         * @return array
         * @static
         */
        public static function uniqueMiddleware($middleware)
        {
            return \Illuminate\Routing\Router::uniqueMiddleware($middleware);
        }

        /**
         * Set the container instance used by the router.
         *
         * @param \Illuminate\Container\Container $container
         * @return \Illuminate\Routing\Router
         * @static
         */
        public static function setContainer($container)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->setContainer($container);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Routing\Router::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void
         * @throws \ReflectionException
         * @static
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Routing\Router::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name)
        {
            return \Illuminate\Routing\Router::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void
         * @static
         */
        public static function flushMacros()
        {
            \Illuminate\Routing\Router::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed
         * @throws \BadMethodCallException
         * @static
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->macroCall($method, $parameters);
        }

        /**
         * Call the given Closure with this instance then return the instance.
         *
         * @param (callable($this): mixed)|null $callback
         * @return ($callback is null ? \Illuminate\Support\HigherOrderTapProxy : $this)
         * @static
         */
        public static function tap($callback = null)
        {
            /** @var \Illuminate\Routing\Router $instance */
            return $instance->tap($callback);
        }

        /**
         * @see \Lab404\Impersonate\ImpersonateServiceProvider::registerRoutesMacro()
         * @static
         */
        public static function impersonate()
        {
            return \Illuminate\Routing\Router::impersonate();
        }

            }
    }

namespace Barryvdh\Debugbar\Facades {
    /**
     * @method static void alert(mixed $message)
     * @method static void critical(mixed $message)
     * @method static void debug(mixed $message)
     * @method static void emergency(mixed $message)
     * @method static void error(mixed $message)
     * @method static void info(mixed $message)
     * @method static void log(mixed $message)
     * @method static void notice(mixed $message)
     * @method static void warning(mixed $message)
     * @see \Barryvdh\Debugbar\LaravelDebugbar
     */
    class Debugbar extends \DebugBar\DebugBar {
        /**
         * Returns the HTTP driver
         * 
         * If no http driver where defined, a PhpHttpDriver is automatically created
         *
         * @return \DebugBar\HttpDriverInterface
         * @static
         */
        public static function getHttpDriver()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getHttpDriver();
        }

        /**
         * Enable the Debugbar and boot, if not already booted.
         *
         * @static
         */
        public static function enable()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->enable();
        }

        /**
         * Boot the debugbar (add collectors, renderer and listener)
         *
         * @static
         */
        public static function boot()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->boot();
        }

        /**
         * @static
         */
        public static function shouldCollect($name, $default = false)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->shouldCollect($name, $default);
        }

        /**
         * Adds a data collector
         *
         * @param \DebugBar\DataCollector\DataCollectorInterface $collector
         * @throws DebugBarException
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function addCollector($collector)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->addCollector($collector);
        }

        /**
         * Handle silenced errors
         *
         * @param $level
         * @param $message
         * @param string $file
         * @param int $line
         * @param array $context
         * @throws \ErrorException
         * @static
         */
        public static function handleError($level, $message, $file = '', $line = 0, $context = [])
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->handleError($level, $message, $file, $line, $context);
        }

        /**
         * Starts a measure
         *
         * @param string $name Internal name, used to stop the measure
         * @param string $label Public name
         * @param string|null $collector
         * @param string|null $group
         * @static
         */
        public static function startMeasure($name, $label = null, $collector = null, $group = null)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->startMeasure($name, $label, $collector, $group);
        }

        /**
         * Stops a measure
         *
         * @param string $name
         * @static
         */
        public static function stopMeasure($name)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->stopMeasure($name);
        }

        /**
         * Adds an exception to be profiled in the debug bar
         *
         * @param \Exception $e
         * @deprecated in favor of addThrowable
         * @static
         */
        public static function addException($e)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->addException($e);
        }

        /**
         * Adds an exception to be profiled in the debug bar
         *
         * @param \Throwable $e
         * @static
         */
        public static function addThrowable($e)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->addThrowable($e);
        }

        /**
         * Returns a JavascriptRenderer for this instance
         *
         * @param string $baseUrl
         * @param string $basePath
         * @return \Barryvdh\Debugbar\JavascriptRenderer
         * @static
         */
        public static function getJavascriptRenderer($baseUrl = null, $basePath = null)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getJavascriptRenderer($baseUrl, $basePath);
        }

        /**
         * Modify the response and inject the debugbar (or data in headers)
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @param \Symfony\Component\HttpFoundation\Response $response
         * @return \Symfony\Component\HttpFoundation\Response
         * @static
         */
        public static function modifyResponse($request, $response)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->modifyResponse($request, $response);
        }

        /**
         * Check if the Debugbar is enabled
         *
         * @return boolean
         * @static
         */
        public static function isEnabled()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->isEnabled();
        }

        /**
         * Collects the data from the collectors
         *
         * @return array
         * @static
         */
        public static function collect()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->collect();
        }

        /**
         * Injects the web debug toolbar into the given Response.
         *
         * @param \Symfony\Component\HttpFoundation\Response $response A Response instance
         * Based on https://github.com/symfony/WebProfilerBundle/blob/master/EventListener/WebDebugToolbarListener.php
         * @static
         */
        public static function injectDebugbar($response)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->injectDebugbar($response);
        }

        /**
         * Checks if there is stacked data in the session
         *
         * @return boolean
         * @static
         */
        public static function hasStackedData()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->hasStackedData();
        }

        /**
         * Returns the data stacked in the session
         *
         * @param boolean $delete Whether to delete the data in the session
         * @return array
         * @static
         */
        public static function getStackedData($delete = true)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getStackedData($delete);
        }

        /**
         * Disable the Debugbar
         *
         * @static
         */
        public static function disable()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->disable();
        }

        /**
         * Adds a measure
         *
         * @param string $label
         * @param float $start
         * @param float $end
         * @param array|null $params
         * @param string|null $collector
         * @param string|null $group
         * @static
         */
        public static function addMeasure($label, $start, $end, $params = [], $collector = null, $group = null)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->addMeasure($label, $start, $end, $params, $collector, $group);
        }

        /**
         * Utility function to measure the execution of a Closure
         *
         * @param string $label
         * @param \Closure $closure
         * @param string|null $collector
         * @param string|null $group
         * @return mixed
         * @static
         */
        public static function measure($label, $closure, $collector = null, $group = null)
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->measure($label, $closure, $collector, $group);
        }

        /**
         * Collect data in a CLI request
         *
         * @return array
         * @static
         */
        public static function collectConsole()
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->collectConsole();
        }

        /**
         * Adds a message to the MessagesCollector
         * 
         * A message can be anything from an object to a string
         *
         * @param mixed $message
         * @param string $label
         * @static
         */
        public static function addMessage($message, $label = 'info')
        {
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->addMessage($message, $label);
        }

        /**
         * Checks if a data collector has been added
         *
         * @param string $name
         * @return boolean
         * @static
         */
        public static function hasCollector($name)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->hasCollector($name);
        }

        /**
         * Returns a data collector
         *
         * @param string $name
         * @return \DebugBar\DataCollector\DataCollectorInterface
         * @throws DebugBarException
         * @static
         */
        public static function getCollector($name)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getCollector($name);
        }

        /**
         * Returns an array of all data collectors
         *
         * @return array[DataCollectorInterface]
         * @static
         */
        public static function getCollectors()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getCollectors();
        }

        /**
         * Sets the request id generator
         *
         * @param \DebugBar\RequestIdGeneratorInterface $generator
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function setRequestIdGenerator($generator)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->setRequestIdGenerator($generator);
        }

        /**
         * @return \DebugBar\RequestIdGeneratorInterface
         * @static
         */
        public static function getRequestIdGenerator()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getRequestIdGenerator();
        }

        /**
         * Returns the id of the current request
         *
         * @return string
         * @static
         */
        public static function getCurrentRequestId()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getCurrentRequestId();
        }

        /**
         * Sets the storage backend to use to store the collected data
         *
         * @param \DebugBar\StorageInterface $storage
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function setStorage($storage = null)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->setStorage($storage);
        }

        /**
         * @return \DebugBar\StorageInterface
         * @static
         */
        public static function getStorage()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getStorage();
        }

        /**
         * Checks if the data will be persisted
         *
         * @return boolean
         * @static
         */
        public static function isDataPersisted()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->isDataPersisted();
        }

        /**
         * Sets the HTTP driver
         *
         * @param \DebugBar\HttpDriverInterface $driver
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function setHttpDriver($driver)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->setHttpDriver($driver);
        }

        /**
         * Returns collected data
         * 
         * Will collect the data if none have been collected yet
         *
         * @return array
         * @static
         */
        public static function getData()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getData();
        }

        /**
         * Returns an array of HTTP headers containing the data
         *
         * @param string $headerName
         * @param integer $maxHeaderLength
         * @return array
         * @static
         */
        public static function getDataAsHeaders($headerName = 'phpdebugbar', $maxHeaderLength = 4096, $maxTotalHeaderLength = 250000)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getDataAsHeaders($headerName, $maxHeaderLength, $maxTotalHeaderLength);
        }

        /**
         * Sends the data through the HTTP headers
         *
         * @param bool $useOpenHandler
         * @param string $headerName
         * @param integer $maxHeaderLength
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function sendDataInHeaders($useOpenHandler = null, $headerName = 'phpdebugbar', $maxHeaderLength = 4096)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->sendDataInHeaders($useOpenHandler, $headerName, $maxHeaderLength);
        }

        /**
         * Stacks the data in the session for later rendering
         *
         * @static
         */
        public static function stackData()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->stackData();
        }

        /**
         * Sets the key to use in the $_SESSION array
         *
         * @param string $ns
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function setStackDataSessionNamespace($ns)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->setStackDataSessionNamespace($ns);
        }

        /**
         * Returns the key used in the $_SESSION array
         *
         * @return string
         * @static
         */
        public static function getStackDataSessionNamespace()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->getStackDataSessionNamespace();
        }

        /**
         * Sets whether to only use the session to store stacked data even
         * if a storage is enabled
         *
         * @param boolean $enabled
         * @return \Barryvdh\Debugbar\LaravelDebugbar
         * @static
         */
        public static function setStackAlwaysUseSessionStorage($enabled = true)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->setStackAlwaysUseSessionStorage($enabled);
        }

        /**
         * Checks if the session is always used to store stacked data
         * even if a storage is enabled
         *
         * @return boolean
         * @static
         */
        public static function isStackAlwaysUseSessionStorage()
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->isStackAlwaysUseSessionStorage();
        }

        /**
         * @static
         */
        public static function offsetSet($key, $value)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->offsetSet($key, $value);
        }

        /**
         * @static
         */
        public static function offsetGet($key)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->offsetGet($key);
        }

        /**
         * @static
         */
        public static function offsetExists($key)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->offsetExists($key);
        }

        /**
         * @static
         */
        public static function offsetUnset($key)
        {
            //Method inherited from \DebugBar\DebugBar 
            /** @var \Barryvdh\Debugbar\LaravelDebugbar $instance */
            return $instance->offsetUnset($key);
        }

            }
    }

namespace Barryvdh\DomPDF\Facade {
    /**
     * @method static BasePDF setBaseHost(string $baseHost)
     * @method static BasePDF setBasePath(string $basePath)
     * @method static BasePDF setCanvas(\Dompdf\Canvas $canvas)
     * @method static BasePDF setCallbacks(array<string, mixed> $callbacks)
     * @method static BasePDF setCss(\Dompdf\Css\Stylesheet $css)
     * @method static BasePDF setDefaultView(string $defaultView, array<string, mixed> $options)
     * @method static BasePDF setDom(\DOMDocument $dom)
     * @method static BasePDF setFontMetrics(\Dompdf\FontMetrics $fontMetrics)
     * @method static BasePDF setHttpContext(resource|array<string, mixed> $httpContext)
     * @method static BasePDF setPaper(string|float[] $paper, string $orientation = 'portrait')
     * @method static BasePDF setProtocol(string $protocol)
     * @method static BasePDF setTree(\Dompdf\Frame\FrameTree $tree)
     */
    class Pdf {
        /**
         * Get the DomPDF instance
         *
         * @static
         */
        public static function getDomPDF()
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->getDomPDF();
        }

        /**
         * Show or hide warnings
         *
         * @static
         */
        public static function setWarnings($warnings)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setWarnings($warnings);
        }

        /**
         * Load a HTML string
         *
         * @param string|null $encoding Not used yet
         * @static
         */
        public static function loadHTML($string, $encoding = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadHTML($string, $encoding);
        }

        /**
         * Load a HTML file
         *
         * @static
         */
        public static function loadFile($file)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadFile($file);
        }

        /**
         * Add metadata info
         *
         * @param array<string, string> $info
         * @static
         */
        public static function addInfo($info)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->addInfo($info);
        }

        /**
         * Load a View and convert to HTML
         *
         * @param array<string, mixed> $data
         * @param array<string, mixed> $mergeData
         * @param string|null $encoding Not used yet
         * @static
         */
        public static function loadView($view, $data = [], $mergeData = [], $encoding = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadView($view, $data, $mergeData, $encoding);
        }

        /**
         * Set/Change an option (or array of options) in Dompdf
         *
         * @param array<string, mixed>|string $attribute
         * @param null|mixed $value
         * @static
         */
        public static function setOption($attribute, $value = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setOption($attribute, $value);
        }

        /**
         * Replace all the Options from DomPDF
         *
         * @param array<string, mixed> $options
         * @static
         */
        public static function setOptions($options, $mergeWithDefaults = false)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setOptions($options, $mergeWithDefaults);
        }

        /**
         * Output the PDF as a string.
         * 
         * The options parameter controls the output. Accepted options are:
         * 
         * 'compress' = > 1 or 0 - apply content stream compression, this is
         *    on (1) by default
         *
         * @param array<string, int> $options
         * @return string The rendered PDF as string
         * @static
         */
        public static function output($options = [])
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->output($options);
        }

        /**
         * Save the PDF to a file
         *
         * @static
         */
        public static function save($filename, $disk = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->save($filename, $disk);
        }

        /**
         * Make the PDF downloadable by the user
         *
         * @static
         */
        public static function download($filename = 'document.pdf')
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->download($filename);
        }

        /**
         * Return a response with the PDF to show in the browser
         *
         * @static
         */
        public static function stream($filename = 'document.pdf')
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->stream($filename);
        }

        /**
         * Render the PDF
         *
         * @static
         */
        public static function render()
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->render();
        }

        /**
         * @param array<string> $pc
         * @static
         */
        public static function setEncryption($password, $ownerpassword = '', $pc = [])
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setEncryption($password, $ownerpassword, $pc);
        }

            }
    /**
     * @method static BasePDF setBaseHost(string $baseHost)
     * @method static BasePDF setBasePath(string $basePath)
     * @method static BasePDF setCanvas(\Dompdf\Canvas $canvas)
     * @method static BasePDF setCallbacks(array<string, mixed> $callbacks)
     * @method static BasePDF setCss(\Dompdf\Css\Stylesheet $css)
     * @method static BasePDF setDefaultView(string $defaultView, array<string, mixed> $options)
     * @method static BasePDF setDom(\DOMDocument $dom)
     * @method static BasePDF setFontMetrics(\Dompdf\FontMetrics $fontMetrics)
     * @method static BasePDF setHttpContext(resource|array<string, mixed> $httpContext)
     * @method static BasePDF setPaper(string|float[] $paper, string $orientation = 'portrait')
     * @method static BasePDF setProtocol(string $protocol)
     * @method static BasePDF setTree(\Dompdf\Frame\FrameTree $tree)
     */
    class Pdf {
        /**
         * Get the DomPDF instance
         *
         * @static
         */
        public static function getDomPDF()
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->getDomPDF();
        }

        /**
         * Show or hide warnings
         *
         * @static
         */
        public static function setWarnings($warnings)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setWarnings($warnings);
        }

        /**
         * Load a HTML string
         *
         * @param string|null $encoding Not used yet
         * @static
         */
        public static function loadHTML($string, $encoding = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadHTML($string, $encoding);
        }

        /**
         * Load a HTML file
         *
         * @static
         */
        public static function loadFile($file)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadFile($file);
        }

        /**
         * Add metadata info
         *
         * @param array<string, string> $info
         * @static
         */
        public static function addInfo($info)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->addInfo($info);
        }

        /**
         * Load a View and convert to HTML
         *
         * @param array<string, mixed> $data
         * @param array<string, mixed> $mergeData
         * @param string|null $encoding Not used yet
         * @static
         */
        public static function loadView($view, $data = [], $mergeData = [], $encoding = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->loadView($view, $data, $mergeData, $encoding);
        }

        /**
         * Set/Change an option (or array of options) in Dompdf
         *
         * @param array<string, mixed>|string $attribute
         * @param null|mixed $value
         * @static
         */
        public static function setOption($attribute, $value = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setOption($attribute, $value);
        }

        /**
         * Replace all the Options from DomPDF
         *
         * @param array<string, mixed> $options
         * @static
         */
        public static function setOptions($options, $mergeWithDefaults = false)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setOptions($options, $mergeWithDefaults);
        }

        /**
         * Output the PDF as a string.
         * 
         * The options parameter controls the output. Accepted options are:
         * 
         * 'compress' = > 1 or 0 - apply content stream compression, this is
         *    on (1) by default
         *
         * @param array<string, int> $options
         * @return string The rendered PDF as string
         * @static
         */
        public static function output($options = [])
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->output($options);
        }

        /**
         * Save the PDF to a file
         *
         * @static
         */
        public static function save($filename, $disk = null)
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->save($filename, $disk);
        }

        /**
         * Make the PDF downloadable by the user
         *
         * @static
         */
        public static function download($filename = 'document.pdf')
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->download($filename);
        }

        /**
         * Return a response with the PDF to show in the browser
         *
         * @static
         */
        public static function stream($filename = 'document.pdf')
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->stream($filename);
        }

        /**
         * Render the PDF
         *
         * @static
         */
        public static function render()
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->render();
        }

        /**
         * @param array<string> $pc
         * @static
         */
        public static function setEncryption($password, $ownerpassword = '', $pc = [])
        {
            /** @var \Barryvdh\DomPDF\PDF $instance */
            return $instance->setEncryption($password, $ownerpassword, $pc);
        }

            }
    }

namespace Laravel\Socialite\Facades {
    /**
     */
    class Socialite extends \Illuminate\Support\Manager {
        /**
         * Get a driver instance.
         *
         * @param string $driver
         * @return mixed
         * @static
         */
        public static function with($driver)
        {
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->with($driver);
        }

        /**
         * Build an OAuth 2 provider instance.
         *
         * @param string $provider
         * @param array $config
         * @return \Laravel\Socialite\Two\AbstractProvider
         * @static
         */
        public static function buildProvider($provider, $config)
        {
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->buildProvider($provider, $config);
        }

        /**
         * Format the server configuration.
         *
         * @param array $config
         * @return array
         * @static
         */
        public static function formatConfig($config)
        {
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->formatConfig($config);
        }

        /**
         * Forget all of the resolved driver instances.
         *
         * @return \Laravel\Socialite\SocialiteManager
         * @static
         */
        public static function forgetDrivers()
        {
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->forgetDrivers();
        }

        /**
         * Set the container instance used by the manager.
         *
         * @param \Illuminate\Contracts\Container\Container $container
         * @return \Laravel\Socialite\SocialiteManager
         * @static
         */
        public static function setContainer($container)
        {
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->setContainer($container);
        }

        /**
         * Get the default driver name.
         *
         * @return string
         * @throws \InvalidArgumentException
         * @static
         */
        public static function getDefaultDriver()
        {
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->getDefaultDriver();
        }

        /**
         * Get a driver instance.
         *
         * @param string|null $driver
         * @return mixed
         * @throws \InvalidArgumentException
         * @static
         */
        public static function driver($driver = null)
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->driver($driver);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return \Laravel\Socialite\SocialiteManager
         * @static
         */
        public static function extend($driver, $callback)
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->extend($driver, $callback);
        }

        /**
         * Get all of the created "drivers".
         *
         * @return array
         * @static
         */
        public static function getDrivers()
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->getDrivers();
        }

        /**
         * Get the container instance used by the manager.
         *
         * @return \Illuminate\Contracts\Container\Container
         * @static
         */
        public static function getContainer()
        {
            //Method inherited from \Illuminate\Support\Manager 
            /** @var \Laravel\Socialite\SocialiteManager $instance */
            return $instance->getContainer();
        }

            }
    }

namespace Maatwebsite\Excel\Facades {
    /**
     */
    class Excel {
        /**
         * @param object $export
         * @param string|null $fileName
         * @param string $writerType
         * @param array $headers
         * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @static
         */
        public static function download($export, $fileName, $writerType = null, $headers = [])
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->download($export, $fileName, $writerType, $headers);
        }

        /**
         * @param string|null $disk Fallback for usage with named properties
         * @param object $export
         * @param string $filePath
         * @param string|null $diskName
         * @param string $writerType
         * @param mixed $diskOptions
         * @return bool
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
         * @static
         */
        public static function store($export, $filePath, $diskName = null, $writerType = null, $diskOptions = [], $disk = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->store($export, $filePath, $diskName, $writerType, $diskOptions, $disk);
        }

        /**
         * @param object $export
         * @param string $filePath
         * @param string|null $disk
         * @param string $writerType
         * @param mixed $diskOptions
         * @return \Illuminate\Foundation\Bus\PendingDispatch
         * @static
         */
        public static function queue($export, $filePath, $disk = null, $writerType = null, $diskOptions = [])
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->queue($export, $filePath, $disk, $writerType, $diskOptions);
        }

        /**
         * @param object $export
         * @param string $writerType
         * @return string
         * @static
         */
        public static function raw($export, $writerType)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->raw($export, $writerType);
        }

        /**
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return \Maatwebsite\Excel\Reader|\Illuminate\Foundation\Bus\PendingDispatch
         * @static
         */
        public static function import($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->import($import, $filePath, $disk, $readerType);
        }

        /**
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return array
         * @static
         */
        public static function toArray($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->toArray($import, $filePath, $disk, $readerType);
        }

        /**
         * @param object $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string|null $readerType
         * @return \Illuminate\Support\Collection
         * @static
         */
        public static function toCollection($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->toCollection($import, $filePath, $disk, $readerType);
        }

        /**
         * @param \Illuminate\Contracts\Queue\ShouldQueue $import
         * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $filePath
         * @param string|null $disk
         * @param string $readerType
         * @return \Illuminate\Foundation\Bus\PendingDispatch
         * @static
         */
        public static function queueImport($import, $filePath, $disk = null, $readerType = null)
        {
            /** @var \Maatwebsite\Excel\Excel $instance */
            return $instance->queueImport($import, $filePath, $disk, $readerType);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro)
        {
            \Maatwebsite\Excel\Excel::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void
         * @throws \ReflectionException
         * @static
         */
        public static function mixin($mixin, $replace = true)
        {
            \Maatwebsite\Excel\Excel::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name)
        {
            return \Maatwebsite\Excel\Excel::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void
         * @static
         */
        public static function flushMacros()
        {
            \Maatwebsite\Excel\Excel::flushMacros();
        }

        /**
         * @param string $concern
         * @param callable $handler
         * @param string $event
         * @static
         */
        public static function extend($concern, $handler, $event = 'Maatwebsite\\Excel\\Events\\BeforeWriting')
        {
            return \Maatwebsite\Excel\Excel::extend($concern, $handler, $event);
        }

        /**
         * When asserting downloaded, stored, queued or imported, use regular expression
         * to look for a matching file path.
         *
         * @return void
         * @static
         */
        public static function matchByRegex()
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            $instance->matchByRegex();
        }

        /**
         * When asserting downloaded, stored, queued or imported, use regular string
         * comparison for matching file path.
         *
         * @return void
         * @static
         */
        public static function doNotMatchByRegex()
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            $instance->doNotMatchByRegex();
        }

        /**
         * @param string $fileName
         * @param callable|null $callback
         * @static
         */
        public static function assertDownloaded($fileName, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertDownloaded($fileName, $callback);
        }

        /**
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static
         */
        public static function assertStored($filePath, $disk = null, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertStored($filePath, $disk, $callback);
        }

        /**
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static
         */
        public static function assertQueued($filePath, $disk = null, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertQueued($filePath, $disk, $callback);
        }

        /**
         * @static
         */
        public static function assertQueuedWithChain($chain)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertQueuedWithChain($chain);
        }

        /**
         * @param string $classname
         * @param callable|null $callback
         * @static
         */
        public static function assertExportedInRaw($classname, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertExportedInRaw($classname, $callback);
        }

        /**
         * @param string $filePath
         * @param string|callable|null $disk
         * @param callable|null $callback
         * @static
         */
        public static function assertImported($filePath, $disk = null, $callback = null)
        {
            /** @var \Maatwebsite\Excel\Fakes\ExcelFake $instance */
            return $instance->assertImported($filePath, $disk, $callback);
        }

            }
    }

namespace Spatie\LaravelBlink {
    /**
     * @see \Spatie\LaravelBlink\LaravelBlinkClass
     */
    class BlinkFacade {
        /**
         * Get always the same instance within the current request
         *
         * @return static
         * @static
         */
        public static function global()
        {
            return \Spatie\Blink\Blink::global();
        }

        /**
         * Put a value in the store.
         *
         * @param string|array $key
         * @param mixed $value
         * @return \Spatie\Blink\Blink
         * @static
         */
        public static function put($key, $value = null)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->put($key, $value);
        }

        /**
         * Get a value from the store.
         * 
         * This function has support for the '*' wildcard.
         *
         * @param string $key
         * @param mixed $default
         * @return null|string|array
         * @static
         */
        public static function get($key, $default = null)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->get($key, $default);
        }

        /**
         * Determine if the store has a value for the given name.
         * 
         * This function has support for the '*' wildcard.
         *
         * @static
         */
        public static function has($key)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->has($key);
        }

        /**
         * Get all values from the store.
         *
         * @return array
         * @static
         */
        public static function all()
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->all();
        }

        /**
         * Get all keys starting with a given string from the store.
         *
         * @param string $startingWith
         * @return array
         * @static
         */
        public static function allStartingWith($startingWith = '')
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->allStartingWith($startingWith);
        }

        /**
         * Forget a value from the store.
         * 
         * This function has support for the '*' wildcard.
         *
         * @param string $key
         * @return \Spatie\Blink\Blink
         * @static
         */
        public static function forget($key)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->forget($key);
        }

        /**
         * Flush all values from the store.
         *
         * @return \Spatie\Blink\Blink
         * @static
         */
        public static function flush()
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->flush();
        }

        /**
         * Flush all values from the store which keys start with a given string.
         *
         * @param string $startingWith
         * @return \Spatie\Blink\Blink
         * @static
         */
        public static function flushStartingWith($startingWith = '')
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->flushStartingWith($startingWith);
        }

        /**
         * Get and forget a value from the store.
         * 
         * This function has support for the '*' wildcard.
         *
         * @param string $key
         * @return null|string
         * @static
         */
        public static function pull($key)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->pull($key);
        }

        /**
         * Increment a value from the store.
         *
         * @param string $key
         * @param int $by
         * @return int|null|string
         * @static
         */
        public static function increment($key, $by = 1)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->increment($key, $by);
        }

        /**
         * Decrement a value from the store.
         *
         * @param string $key
         * @param int $by
         * @return int|null|string
         * @static
         */
        public static function decrement($key, $by = 1)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->decrement($key, $by);
        }

        /**
         * Whether a offset exists.
         *
         * @link http://php.net/manual/en/arrayaccess.offsetexists.php
         * @param mixed $offset
         * @return bool
         * @static
         */
        public static function offsetExists($offset)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->offsetExists($offset);
        }

        /**
         * Offset to retrieve.
         *
         * @link http://php.net/manual/en/arrayaccess.offsetget.php
         * @param mixed $offset
         * @return mixed
         * @static
         */
        public static function offsetGet($offset)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->offsetGet($offset);
        }

        /**
         * Offset to set.
         *
         * @link http://php.net/manual/en/arrayaccess.offsetset.php
         * @param mixed $offset
         * @param mixed $value
         * @static
         */
        public static function offsetSet($offset, $value)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->offsetSet($offset, $value);
        }

        /**
         * Offset to unset.
         *
         * @link http://php.net/manual/en/arrayaccess.offsetunset.php
         * @param mixed $offset
         * @static
         */
        public static function offsetUnset($offset)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->offsetUnset($offset);
        }

        /**
         * Count elements.
         *
         * @link http://php.net/manual/en/countable.count.php
         * @return int
         * @static
         */
        public static function count()
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->count();
        }

        /**
         * Only if the given key is not present in the blink cache the callable will be executed.
         * 
         * The result of the callable will be stored in the given key and returned.
         *
         * @param $key
         * @param callable $callable
         * @return mixed
         * @static
         */
        public static function once($key, $callable)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->once($key, $callable);
        }

        /**
         * Use the "once" method only if the given condition is true.
         * 
         * Otherwise, the callable will be executed.
         *
         * @return mixed
         * @static
         */
        public static function onceIf($shouldBlink, $key, $callable)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->onceIf($shouldBlink, $key, $callable);
        }

        /**
         * @static
         */
        public static function getValuesForKeys($keys)
        {
            /** @var \Spatie\Blink\Blink $instance */
            return $instance->getValuesForKeys($keys);
        }

            }
    }

namespace Vinkla\Hashids\Facades {
    /**
     * @method static string encode(mixed ...$numbers)
     * @method static array decode(string $hash)
     * @method static string encodeHex(string $str)
     * @method static string decodeHex(string $hash)
     */
    class Hashids extends \GrahamCampbell\Manager\AbstractManager {
        /**
         * @static
         */
        public static function getFactory()
        {
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->getFactory();
        }

        /**
         * Get a connection instance.
         *
         * @param string|null $name
         * @throws \InvalidArgumentException
         * @return object
         * @static
         */
        public static function connection($name = null)
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->connection($name);
        }

        /**
         * Reconnect to the given connection.
         *
         * @param string|null $name
         * @throws \InvalidArgumentException
         * @return object
         * @static
         */
        public static function reconnect($name = null)
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->reconnect($name);
        }

        /**
         * Disconnect from the given connection.
         *
         * @param string|null $name
         * @return void
         * @static
         */
        public static function disconnect($name = null)
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            $instance->disconnect($name);
        }

        /**
         * Get the configuration for a connection.
         *
         * @param string|null $name
         * @throws \InvalidArgumentException
         * @return array
         * @static
         */
        public static function getConnectionConfig($name = null)
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->getConnectionConfig($name);
        }

        /**
         * Get the default connection name.
         *
         * @return string
         * @static
         */
        public static function getDefaultConnection()
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->getDefaultConnection();
        }

        /**
         * Set the default connection name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultConnection($name)
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            $instance->setDefaultConnection($name);
        }

        /**
         * Register an extension connection resolver.
         *
         * @param string $name
         * @param callable $resolver
         * @return void
         * @static
         */
        public static function extend($name, $resolver)
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            $instance->extend($name, $resolver);
        }

        /**
         * Return all of the created connections.
         *
         * @return array<string,object>
         * @static
         */
        public static function getConnections()
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->getConnections();
        }

        /**
         * Get the config instance.
         *
         * @return \Illuminate\Contracts\Config\Repository
         * @static
         */
        public static function getConfig()
        {
            //Method inherited from \GrahamCampbell\Manager\AbstractManager 
            /** @var \Vinkla\Hashids\HashidsManager $instance */
            return $instance->getConfig();
        }

            }
    }

namespace Yajra\DataTables\Facades {
    /**
     * @mixin \Yajra\DataTables\DataTables
     * @see \Yajra\DataTables\DataTables
     */
    class DataTables {
        /**
         * Make a DataTable instance from source.
         * 
         * Alias of make for backward compatibility.
         *
         * @param object $source
         * @return \Yajra\DataTables\DataTableAbstract
         * @throws \Exception
         * @static
         */
        public static function of($source)
        {
            return \Yajra\DataTables\DataTables::of($source);
        }

        /**
         * Make a DataTable instance from source.
         *
         * @param object $source
         * @return \Yajra\DataTables\DataTableAbstract
         * @throws \Yajra\DataTables\Exceptions\Exception
         * @static
         */
        public static function make($source)
        {
            return \Yajra\DataTables\DataTables::make($source);
        }

        /**
         * Get request object.
         *
         * @static
         */
        public static function getRequest()
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->getRequest();
        }

        /**
         * Get config instance.
         *
         * @static
         */
        public static function getConfig()
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->getConfig();
        }

        /**
         * DataTables using query builder.
         *
         * @throws \Yajra\DataTables\Exceptions\Exception
         * @static
         */
        public static function query($builder)
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->query($builder);
        }

        /**
         * DataTables using Eloquent Builder.
         *
         * @throws \Yajra\DataTables\Exceptions\Exception
         * @static
         */
        public static function eloquent($builder)
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->eloquent($builder);
        }

        /**
         * DataTables using Collection.
         *
         * @param \Illuminate\Support\Collection<array-key, array>|array $collection
         * @throws \Yajra\DataTables\Exceptions\Exception
         * @static
         */
        public static function collection($collection)
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->collection($collection);
        }

        /**
         * DataTables using Collection.
         *
         * @param \Illuminate\Http\Resources\Json\AnonymousResourceCollection<array-key, array>|array $resource
         * @return \Yajra\DataTables\ApiResourceDataTable|\Yajra\DataTables\DataTableAbstract
         * @static
         */
        public static function resource($resource)
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->resource($resource);
        }

        /**
         * @throws \Yajra\DataTables\Exceptions\Exception
         * @static
         */
        public static function validateDataTable($engine, $parent)
        {
            /** @var \Yajra\DataTables\DataTables $instance */
            return $instance->validateDataTable($engine, $parent);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro)
        {
            \Yajra\DataTables\DataTables::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void
         * @throws \ReflectionException
         * @static
         */
        public static function mixin($mixin, $replace = true)
        {
            \Yajra\DataTables\DataTables::mixin($mixin, $replace);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name)
        {
            return \Yajra\DataTables\DataTables::hasMacro($name);
        }

        /**
         * Flush the existing macros.
         *
         * @return void
         * @static
         */
        public static function flushMacros()
        {
            \Yajra\DataTables\DataTables::flushMacros();
        }

            }
    }

namespace Illuminate\Support {
    /**
     * @template TKey of array-key
     * @template-covariant TValue
     * @implements \ArrayAccess<TKey, TValue>
     * @implements \Illuminate\Support\Enumerable<TKey, TValue>
     */
    class Collection {
        /**
         * @see \Barryvdh\Debugbar\ServiceProvider::register()
         * @static
         */
        public static function debug()
        {
            return \Illuminate\Support\Collection::debug();
        }

        /**
         * @see \Maatwebsite\Excel\Mixins\DownloadCollectionMixin::downloadExcel()
         * @param string $fileName
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @param array $responseHeaders
         * @static
         */
        public static function downloadExcel($fileName, $writerType = null, $withHeadings = false, $responseHeaders = [])
        {
            return \Illuminate\Support\Collection::downloadExcel($fileName, $writerType, $withHeadings, $responseHeaders);
        }

        /**
         * @see \Maatwebsite\Excel\Mixins\StoreCollectionMixin::storeExcel()
         * @param string $filePath
         * @param string|null $disk
         * @param string|null $writerType
         * @param mixed $withHeadings
         * @static
         */
        public static function storeExcel($filePath, $disk = null, $writerType = null, $withHeadings = false)
        {
            return \Illuminate\Support\Collection::storeExcel($filePath, $disk, $writerType, $withHeadings);
        }

            }
    }

namespace Illuminate\Http {
    /**
     */
    class Request extends \Symfony\Component\HttpFoundation\Request {
        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param array $rules
         * @param mixed $params
         * @static
         */
        public static function validate($rules, ...$params)
        {
            return \Illuminate\Http\Request::validate($rules, ...$params);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param string $errorBag
         * @param array $rules
         * @param mixed $params
         * @static
         */
        public static function validateWithBag($errorBag, $rules, ...$params)
        {
            return \Illuminate\Http\Request::validateWithBag($errorBag, $rules, ...$params);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $absolute
         * @static
         */
        public static function hasValidSignature($absolute = true)
        {
            return \Illuminate\Http\Request::hasValidSignature($absolute);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @static
         */
        public static function hasValidRelativeSignature()
        {
            return \Illuminate\Http\Request::hasValidRelativeSignature();
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @param mixed $absolute
         * @static
         */
        public static function hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
        {
            return \Illuminate\Http\Request::hasValidSignatureWhileIgnoring($ignoreQuery, $absolute);
        }

        /**
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @static
         */
        public static function hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
        {
            return \Illuminate\Http\Request::hasValidRelativeSignatureWhileIgnoring($ignoreQuery);
        }

            }
    }

namespace Illuminate\Routing {
    /**
     * @mixin \Illuminate\Routing\RouteRegistrar
     */
    class Router {
        /**
         * @see \Lab404\Impersonate\ImpersonateServiceProvider::registerRoutesMacro()
         * @static
         */
        public static function impersonate()
        {
            return \Illuminate\Routing\Router::impersonate();
        }

            }
    /**
     */
    class Route {
        /**
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $roles
         * @static
         */
        public static function role($roles = [])
        {
            return \Illuminate\Routing\Route::role($roles);
        }

        /**
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $permissions
         * @static
         */
        public static function permission($permissions = [])
        {
            return \Illuminate\Routing\Route::permission($permissions);
        }

        /**
         * @see \Spatie\Permission\PermissionServiceProvider::registerMacroHelpers()
         * @param mixed $rolesOrPermissions
         * @static
         */
        public static function roleOrPermission($rolesOrPermissions = [])
        {
            return \Illuminate\Routing\Route::roleOrPermission($rolesOrPermissions);
        }

            }
    }


namespace  {
    class Vite extends \Illuminate\Support\Facades\Vite {}
    class Str extends \Illuminate\Support\Str {}
    class Arr extends \Illuminate\Support\Arr {}
    class DB extends \Illuminate\Support\Facades\DB {}
    class Log extends \Illuminate\Support\Facades\Log {}
    class Route extends \Illuminate\Support\Facades\Route {}
    class Debugbar extends \Barryvdh\Debugbar\Facades\Debugbar {}
    class PDF extends \Barryvdh\DomPDF\Facade\Pdf {}
    class Pdf extends \Barryvdh\DomPDF\Facade\Pdf {}
    class Socialite extends \Laravel\Socialite\Facades\Socialite {}
    class Excel extends \Maatwebsite\Excel\Facades\Excel {}
    class Blink extends \Spatie\LaravelBlink\BlinkFacade {}
    class Hashids extends \Vinkla\Hashids\Facades\Hashids {}
    class DataTables extends \Yajra\DataTables\Facades\DataTables {}
}





