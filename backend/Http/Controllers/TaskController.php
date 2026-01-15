class TaskController extends Controller
{
    public function index()
    {
        return response()->json(
            $this->taskService->getAll()
        );
    }
}