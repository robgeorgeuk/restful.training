# config valid only for current version of Capistrano
set :application, "restful.training"
set :repo_url, "git@github.com:develop-me/restful.training.git"

# Default value for :linked_files is []
set :linked_files, fetch(:linked_files, []).push(".env")

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push("storage")

namespace :deploy do
    task :mod_group do
        on roles(:app) do
            execute "sudo chown -R www-data:www-data #{deploy_to}"
            execute "sudo chmod -R 775 #{deploy_to}"
            info "Adding www-data permissions"
        end
    end

    task :php_reload do
        on roles(:app) do
            info "Restarting PHP-FPM"
            execute "sudo service php7.2-fpm reload"
        end
    end

    after :updating, "laravel:create_paths"
    after :updated, "deploy:mod_group"
    after :published, "laravel:optimize"
    after :published, "php_reload"
end
