<?php

namespace App\Livewire\Admin\MessageDirections;

use App\Models\MessageDirection;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Messages de la Direction')]
class MessageIndex extends Component
{
    use WithPagination;

    public function toggleVisibility(int $id): void
    {
        $message = MessageDirection::findOrFail($id);
        $message->visible = ! $message->visible;
        $message->save();

        session()->flash('success', 'Visibilité mise à jour.');
    }

    public function delete(int $id): void
    {
        $message = MessageDirection::findOrFail($id);

        if ($message->photo_path) {
            Storage::disk('public')->delete($message->photo_path);
        }

        $message->delete();

        session()->flash('success', 'Message supprimé.');
        $this->resetPage();
    }

    public function render()
    {
        $messages = MessageDirection::latest()->paginate(10);

        return view('livewire.admin.message-directions.message-index', [
            'messages' => $messages,
        ]);
    }
}
