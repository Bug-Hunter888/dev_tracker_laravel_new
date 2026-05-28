<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);

        $request->validate(['file' => 'required|file|max:10240']);

        $file     = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('attachments', $filename, 'public');

        $task->attachments()->create([
            'user_id'       => auth()->id(),
            'original_name' => $file->getClientOriginalName(),
            'filename'      => $filename,
            'path'          => $path,
            'size'          => $file->getSize(),
            'mime_type'     => $file->getMimeType(),
        ]);

        TaskActivity::create([
            'task_id'   => $task->id,
            'user_id'   => auth()->id(),
            'action'    => 'attached_file',
            'new_value' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'File attached.');
    }

    public function destroy(TaskAttachment $attachment)
    {
        $task = $attachment->task;
        abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return redirect()->route('tasks.show', $task)->with('success', 'Attachment removed.');
    }

    public function download(TaskAttachment $attachment)
    {
        $task = $attachment->task;
        abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }
}
