<?php

namespace App\Http\Controllers;

use App\Mail\JobPosted;
use App\Models\Job;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('employer')->latest()->simplePaginate(3);
        return view('jobs.index', [
            'jobs' => $jobs
        ]);
    }

    public function show(Job $job)
    {
        return view('jobs.show', ['job' => $job]);
    }

    public function store(Job $job)
    {
        request()->validate([
            'title' => ['required', 'min:3'],
            'salary' => ['required']
        ]);
        $job = Job::create([
            'title' => request('title'),
            'salary' => request('salary'),
            'employer_id' => 1
        ]);
        
        Mail::to($job->employer->user)->send(
            new JobPosted($job)
        );

        return redirect('/jobs');
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function edit(Job $job)
    {
        return view('jobs.edit', ['job' => $job]);
    }

    public function update(Job $job)
    {
        //authorize (On hold...)

        request()->validate([
            'title' => ['required', 'min:3'],
            'salary' => ['required']
        ]);

        $job->update([
            'title' => request('title'),
            'salary' => request('salary'),
        ]);

        return redirect('/jobs/' . $job->id);
    }

    public function destroy(Job $job)
    {
        //authorize (On hold...)

        $job->delete();

        return redirect('/jobs');
    }
}
