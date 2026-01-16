<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.landing-layout')]
#[Title('Akmal - Fullstack Web Developer | Jasa Pembuatan Website & Aplikasi')]
class LandingPage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $projectType = '';
    public string $budget = '';
    public string $message = '';
    public bool $showSuccess = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'projectType' => ['required', 'string'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    public function submitContact(): void
    {
        $this->validate();

        // Here you could send email, save to database, etc.
        // For now, just show success message

        $this->showSuccess = true;
        $this->reset(['name', 'email', 'projectType', 'budget', 'message']);
    }

    public function render()
    {
        return view('livewire.public.landing-page');
    }
}
