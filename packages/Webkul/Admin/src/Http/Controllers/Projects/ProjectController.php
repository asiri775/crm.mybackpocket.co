<?php

namespace Webkul\Admin\Http\Controllers\Projects;

use App\Helpers\Constants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\DataGrids\Project\ProjectDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\AttributeForm;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Resources\ProjectResource;
use Webkul\Project\Models\Project;
use Webkul\Project\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProjectRepository $projectRepository)
    {
        request()->request->add(['entity_type' => 'projects']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ProjectDataGrid::class)->process();
        }

        return view('admin::projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projectId = 101;
        $projectModel = Project::orderBy('project_number', 'desc')->first();
        if ($projectModel != null) {
            $projectId = $projectModel->project_number + 1;
        }
        return view('admin::projects.create', compact('projectId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('projects.create.before');

        $data = $request->all();

        $projectId = 101;
        $projectModel = Project::orderBy('project_number', 'desc')->first();
        if ($projectModel != null) {
            $projectId = $projectModel->project_number + 1;
        }

        $data['project_number'] = $projectId;
        $data['project_id'] = Constants::PROJECT_PREFIX . "-" . $projectId;
        $data['user_id'] = auth()->user()->id;

        $project = $this->projectRepository->create($data);

        Event::dispatch('projects.create.after', $project);

        session()->flash('success', trans('admin::app.projects.index.create-success'));

        return redirect()->route('admin.projects.index');
    }

    /**
     * Show the form for viewing the specified resource.
     */
    public function view(int $id): View
    {
        $project = $this->projectRepository->findOrFail($id);

        return view('admin::projects.view', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View|JsonResponse
    {
        $project = $this->projectRepository->findOrFail($id);
        return view('admin::projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeForm $request, int $id)
    {
        Event::dispatch('project.update.before', $id);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $project = $this->projectRepository->update($data, $id);

        Event::dispatch('project.update.after', $project);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.projects.index.update-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.projects.index.update-success'));

        return redirect()->route('admin.projects.index');
    }

    /**
     * Search project results
     */
    public function search(): JsonResource
    {
        $projects = $this->projectRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->all();

        return ProjectResource::collection($projects);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $project = $this->projectRepository->findOrFail($id);

        try {
            Event::dispatch('settings.projects.delete.before', $id);

            $project->delete($id);

            Event::dispatch('settings.projects.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.projects.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => trans('admin::app.projects.index.delete-failed'),
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
            Event::dispatch('project.delete.before', $index);

            $this->projectRepository->delete($index);

            Event::dispatch('project.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.projects.index.delete-success'),
        ]);
    }
}
