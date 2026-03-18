<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature   = 'db:create';
    protected $description = 'Cria o banco de dados configurado no .env caso não exista';

    public function handle(): int
    {
        $connection  = config('database.default');
        $driver      = config("database.connections.{$connection}.driver");

        if ($driver !== 'mysql') {
            $this->line("db:create: nada a fazer para o driver [{$driver}].");
            return self::SUCCESS;
        }

        $database    = config("database.connections.{$connection}.database");
        $charset     = config("database.connections.{$connection}.charset",   'utf8mb4');
        $collation   = config("database.connections.{$connection}.collation", 'utf8mb4_unicode_ci');

        // Conecta temporariamente sem selecionar o banco
        config(["database.connections.{$connection}.database" => null]);
        DB::purge($connection);

        DB::statement(
            "CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET {$charset} COLLATE {$collation}"
        );

        // Restaura a conexão normal
        config(["database.connections.{$connection}.database" => $database]);
        DB::purge($connection);

        $this->info("Banco de dados [{$database}] criado ou já existia.");

        return self::SUCCESS;
    }
}
