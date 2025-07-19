<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        // Create the main window
        Window::open()
            ->width(800)
            ->height(800);

        // Create a menu bar with context menu
        MenuBar::create()
            ->label('Task Manager')
            ->tooltip('Click to open Task Manager')
            ->width(400)
            ->height(600)
            ->route('tasks.dashboard')
            ->showDockIcon()
            ->withContextMenu(
                Menu::make(
                    Menu::label('Task Manager'),
                    Menu::separator(),
                    Menu::link(route('tasks.index'), 'View All Tasks'),
                    Menu::link(route('tasks.create'), 'Create New Task'),
                    Menu::link(route('tasks.dashboard'), 'Dashboard'),
                    Menu::separator(),
                    Menu::link('https://nativephp.com', 'Learn more about NativePHP')
                        ->openInBrowser(),
                    Menu::separator(),
                    Menu::quit()
                )
            );
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [];
    }
}
