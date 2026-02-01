import { execSync } from 'child_process';
import type { Plugin } from 'vite';
import { normalizePath } from 'vite';

export interface Watcher {
    /**
     * Command to execute when files matching the pattern change
     */
    command: string;

    /**
     * Glob pattern to watch for file changes
     */
    pattern: string;

    /**
     * Whether to trigger a page refresh after command execution
     * @default true
     */
    refresh?: boolean;
}

export interface LaravelWatcherOptions {
    /**
     * Array of watchers to configure
     */
    watchers?: Watcher[];

    /**
     * Whether to show console messages
     * @default false
     */
    verbose?: boolean;
}

/**
 * Check if a file path matches a glob pattern
 */
const matchesPattern = (path: string, pattern: string): boolean => {
    const regexPattern = pattern
        .replace(/\*\*/g, '.*') // ** matches any number of directories/characters
        .replace(/(?<!\*)\*(?!\*)/g, '[^/]*') // single * matches any characters except /
        .replace(/\./g, '\\.'); // escape dots

    return new RegExp(`^${regexPattern}$`).test(path);
};

/**
 * Vite plugin for auto-executing Laravel artisan commands on file changes
 */
export default function laravelWatcher({
    watchers = [],
    verbose = false,
}: LaravelWatcherOptions): Plugin {
    const {
        info = () => {}, // print messages
        warn = () => {}, // print warnings
    } = verbose ? console : {};

    return <Plugin>{
        name: 'laravel-watcher',

        handleHotUpdate({ file, server }): void {
            // Normalize path for cross-platform compatibility
            const normalizedFile = normalizePath(file);

            // Extract the relative path from the absolute path
            const projectRootMarkers = ['/app/', '/resources/', '/config/'];
            let relativePath = normalizedFile;

            for (const marker of projectRootMarkers) {
                const markerIndex = normalizedFile.indexOf(marker);
                if (markerIndex !== -1) {
                    // Extract path starting from the marker (removing the leading slash)
                    relativePath = normalizedFile.substring(markerIndex + 1);
                    break;
                }
            }

            // Find watchers that match the changed file (using relative path)
            const matchingWatchers = watchers.filter((watcher) => {
                return matchesPattern(relativePath, watcher.pattern);
            });

            // Execute commands for all matching watchers
            matchingWatchers.forEach((watcher) => {
                info(`File change detected: ${normalizedFile}`);

                try {
                    info(`Executing command: ${watcher.command}`);
                    execSync(watcher.command, { stdio: 'inherit' });

                    const refresh = watcher.refresh ?? true;

                    if (refresh) {
                        // Trigger full reload to pick up new types/data
                        server.ws.send({
                            type: 'full-reload',
                            path: '*',
                        });
                    } else {
                        // Send custom event for more targeted updates
                        server.ws.send({
                            type: 'custom',
                            event: 'laravel-watcher-updated',
                            data: {
                                file: normalizedFile,
                                command: watcher.command,
                            },
                        });
                    }
                } catch (error) {
                    const message =
                        error instanceof Error ? error.message : String(error);
                    warn(
                        `Failed to execute command "${watcher.command}":`,
                        message,
                    );
                }
            });
        },
    };
}
