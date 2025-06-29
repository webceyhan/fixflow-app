# FixFlow Copilot Customization

This directory contains custom instructions and prompt files to enhance GitHub Copilot's understanding of the FixFlow device repair management system.

## Directory Structure

```
.github/
├── copilot-instructions.md          # General project-wide instructions
├── instructions/                    # File-type-specific instructions
│   ├── laravel.instructions.md
│   ├── migration.instructions.md
│   ├── pest.instructions.md
│   ├── styling.instructions.md
│   ├── typescript.instructions.md
│   └── vue.instructions.md
└── prompts/                        # Reusable task-specific prompts
    ├── create-model.prompt.md
    ├── create-tests.prompt.md
    ├── create-vue-component.prompt.md
    ├── database-optimization.prompt.md
    └── security-audit.prompt.md
```

## Custom Instructions

### Repository-wide Instructions
- **`.github/copilot-instructions.md`**: General coding standards and project requirements that apply to all file types.

### File-type-specific Instructions
These instructions automatically apply based on file patterns:

- **`vue.instructions.md`**: Vue 3 Composition API, Inertia.js patterns, TypeScript
- **`laravel.instructions.md`**: Laravel conventions, PHP 8.2+ features, security practices
- **`migration.instructions.md`**: Laravel migration best practices, indexing strategies
- **`typescript.instructions.md`**: TypeScript strict typing, modern ES6+ syntax, async patterns
- **`pest.instructions.md`**: Pest testing framework patterns, Laravel testing integration
- **`styling.instructions.md`**: Tailwind CSS utilities, responsive design, accessibility (CSS & Vue)

## Prompt Files

Reusable prompts for common development tasks:

- **`create-model.prompt.md`**: Generate Laravel Eloquent models with relationships
- **`create-vue-component.prompt.md`**: Create Vue components following project patterns
- **`create-tests.prompt.md`**: Generate comprehensive test coverage
- **`security-audit.prompt.md`**: Perform security analysis of code components
- **`database-optimization.prompt.md`**: Analyze and optimize database performance

## Usage

### Automatic Application
File-type-specific instructions are automatically applied when working with matching file types. The `applyTo` property in each instruction file determines when they're activated.

### Using Prompt Files
In VS Code chat, use prompts by typing `/` followed by the prompt name:
- `/create-model`
- `/create-vue-component`
- `/create-tests`
- `/security-audit`
- `/database-optimization`

### Manual Attachment
You can manually attach instruction files to chat prompts using:
- Chat view: **Add Context > Instructions**
- Command Palette: **Chat: Attach Instructions**

## Requirements

Ensure these VS Code settings are enabled:
- `github.copilot.chat.codeGeneration.useInstructionFiles`: true
- `chat.promptFiles`: true

## Customization

Instructions and prompts can be modified to match evolving project requirements. Keep instructions:
- Short and self-contained
- Specific to the task or file type
- Free of external references
- Focused on actionable guidelines

## Reference

- [VS Code Copilot Customization](https://code.visualstudio.com/docs/copilot/copilot-customization)
- [GitHub Copilot Documentation](https://docs.github.com/en/copilot)
