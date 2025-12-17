<?php

namespace Webkul\Admin\Http\Controllers\Projects;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\DataGrids\Project\ProjectTaskDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\AttributeForm;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Resources\ProjectResource;
use Webkul\Project\Models\ProjectTask;
use Webkul\Project\Repositories\ProjectTaskRepository;

class ProjectTaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProjectTaskRepository $projectTaskRepository)
    {
        request()->request->add(['entity_type' => 'projects-tasks']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ProjectTaskDataGrid::class)->process();
        }

        return view('admin::projects.tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projectTask = new ProjectTask();
        $projectTask->priority = "medium";
        $projectTask->status = "open";
        return view('admin::projects.tasks.create', compact('projectTask'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('projects.tasks.create.before');

        $data = $request->all();
        if ($data['project_id'] == "") {
            $data['project_id'] = null;
        }
        $data['user_id'] = auth()->user()->id;
        $projectTask = $this->projectTaskRepository->create($data);

        Event::dispatch('projects.tasks.create.after', $projectTask);

        session()->flash('success', trans('admin::app.projects.tasks.index.create-success'));

        return redirect()->route('admin.projects.tasks.index');
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function view(int $id): View
    {
        $projectTask = $this->projectTaskRepository->findOrFail($id);

        return view('admin::projects.tasks.view', compact('projectTask'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View|JsonResponse
    {
        $projectTask = $this->projectTaskRepository->findOrFail($id);
        return view('admin::projects.tasks.edit', compact('projectTask'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeForm $request, int $id)
    {
        Event::dispatch('project.tasks.update.before', $id);

        $data = $request->all();
        if ($data['project_id'] == "") {
            $data['project_id'] = null;
        }
        $data['user_id'] = auth()->user()->id;
        
        $projectTask = $this->projectTaskRepository->update($data, $id);

        Event::dispatch('project.tasks.update.after', $projectTask);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.projects.tasks.index.update-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.projects.tasks.index.update-success'));

        return redirect()->route('admin.projects.tasks.index');
    }

    /**
     * Search project results
     */
    public function search(): JsonResource
    {
        $projectTasks = $this->projectTaskRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->all();

        return ProjectResource::collection($projectTasks);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $projectTasks = $this->projectTaskRepository->findOrFail($id);

        try {
            Event::dispatch('settings.projects.tasks.delete.before', $id);

            $projectTasks->delete($id);

            Event::dispatch('settings.projects.tasks.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.projects.tasks.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => trans('admin::app.projects.tasks.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            Event::dispatch('project.tasks.delete.before', $index);

            $this->projectTaskRepository->delete($index);

            Event::dispatch('project.tasks.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.projects.tasks.index.delete-success'),
        ]);
    }
}
