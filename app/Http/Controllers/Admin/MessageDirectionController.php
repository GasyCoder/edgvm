<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMessageDirectionRequest;
use App\Models\MessageDirection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class MessageDirectionController extends Controller
{
    public function index(): Response
    {
        $messages = MessageDirection::latest()
            ->paginate(10)
            ->through(fn (MessageDirection $message) => [
                'id' => $message->id,
                'nom' => $message->nom,
                'fonction' => $message->fonction,
                'visible' => $message->visible,
                'photo_url' => $message->photo_path ? Storage::disk('public')->url($message->photo_path) : null,
            ]);

        return Inertia::render('Admin/MessageDirections/Index', [
            'messages' => $messages,
        ]);
    }

    public function edit(MessageDirection $messageDirection): Response
    {
        return Inertia::render('Admin/MessageDirections/Edit', [
            'message' => [
                'id' => $messageDirection->id,
                'nom' => $messageDirection->nom,
                'fonction' => $messageDirection->fonction,
                'institution' => $messageDirection->institution,
                'telephone' => $messageDirection->telephone,
                'email' => $messageDirection->email,
                'citation' => $messageDirection->citation,
                'message_intro' => $messageDirection->message_intro,
                'visible' => $messageDirection->visible,
                'photo_url' => $messageDirection->photo_path ? Storage::disk('public')->url($messageDirection->photo_path) : null,
            ],
        ]);
    }

    public function update(UpdateMessageDirectionRequest $request, MessageDirection $messageDirection): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($messageDirection->photo_path) {
                Storage::disk('public')->delete($messageDirection->photo_path);
            }

            $path = $request->file('photo')->store('message-direction', 'public');
            $data['photo_path'] = $path;
        }

        $messageDirection->update($data);

        return redirect()->route('admin.message-directions.index')
            ->with('success', 'Mot de la Direction mis a jour.');
    }

    public function destroy(MessageDirection $messageDirection): RedirectResponse
    {
        if ($messageDirection->photo_path) {
            Storage::disk('public')->delete($messageDirection->photo_path);
        }

        $messageDirection->delete();

        return redirect()->route('admin.message-directions.index')
            ->with('success', 'Message supprime.');
    }

    public function toggleVisibility(MessageDirection $messageDirection): RedirectResponse
    {
        $messageDirection->update(['visible' => ! $messageDirection->visible]);

        return redirect()->route('admin.message-directions.index')
            ->with('success', 'Visibilite mise a jour.');
    }
}
