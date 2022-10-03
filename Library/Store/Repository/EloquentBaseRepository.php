<?php
namespace Library\Store\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Library\Events\EventsEmitter;
use Library\Query\FilterExpression\SimpleFilterExpression;
use Library\Query\Pagination\PaginatedCollection;
use Library\Query\Pagination\Pagination;

/**
 * Eloquent implementation of repository
 * this way you can keep your queries in the eloquent ecosystem
 */
class EloquentBaseRepository implements BaseRepository,EventsRepository,SimpleFilterReadOnlyRepository {
    use EventsEmitter;


    protected Model $con;
    protected array $_evListeners;
    public function __construct(Model $model) {
        $this->con = $model;
    }



    public function findById(string $id)
    {
        return $this->con->findorFail($id);
    }

    public function findAll(string $id)
    {
        return $this->con->all();
    }

    public function deleteById(string $id) : bool
    {
        $model = $this->findById($id);
        $payload = [$model];
        $isDeleted = !!$model->forceDelete();
        if ($isDeleted)
        {
            $this->emit(EventsRepository::DELETED_EV,$payload);
        }
        return $isDeleted;
    }

    public function findIn(array $id)
    {
        return $this->con->where("id in(?)",$id)->get();
    }

    public function create($payload) : bool
    {

        $model = $this->con->create($payload);
        $hasCreated = !!$model->fresh();
        if ($hasCreated)
        {
            $this->emit(EventsRepository::CREATED_EV,[$model]);
        }
        return $hasCreated;
    }

    public function update($payload) : bool
    {
        $mdl = clone $this->con;
        $hasUpdated = $mdl->update($payload);
        $mdl = $mdl->fresh();
        if ($hasUpdated)
        {
            $this->emit(EventsRepository::UPDATED_EV,[$mdl]);
        }
        return $hasUpdated;
    }

    public function createMany(array $models) : ModifyResponse
    {
        $now = Carbon::now('utc')->toDateTimeString();

        $models = array_map(function ($model) use ($now){
            $model["created_at"] = $now;
            $model["updated_at"] = $now;
            return $model;
        },$models);

        // TODO: Implement createMany() method.


        $isInserted = DB::table($this->con->getTable())->insert($models);
        if ($isInserted)
        {
            $this->emit(EventsRepository::CREATED_EV,$models);
        }
        return ModifyResponse::Init()
            ->WithIsSuccess(true)
            ->WithRowsModified(sizeof($models));
    }

    /**
     * @throws \Exception
     */
    public function updateMany(array $models) : ModifyResponse
    {
        $now = Carbon::now('utc')->toDateTimeString();

        $models = array_map(function ($model) use ($now){
            $model["updated_at"] = $now;
            return $model;
        },$models);



        $isInserted = DB::table($this->con->getTable())->upsert($models,$this->con->getKeyName());
        if ($isInserted)
        {
            $this->emit(EventsRepository::UPDATED_EV,$models);
        }
        return ModifyResponse::Init()
            ->WithIsSuccess(true)
            ->WithRowsModified(sizeof($models));
    }


    function FindAllWithFilter(SimpleFilterExpression $filter): array
    {
        $table = $this->con->getTable();
        $sqlOut = $filter->ToSQL();
        $sql = $sqlOut->GetSQL();
        $args = $sqlOut->GetArgs();

        return DB::select("SELECT * FROM $table where 1=1 AND $sql",$args);
    }

    function FindAllPaginatedAndFilter(Pagination $pagination, SimpleFilterExpression $filter): PaginatedCollection
    {
        $table = $this->con->getTable();
        $sqlOut = $filter->ToSQL();
        $sql = $sqlOut->GetSQL();
        $args = $sqlOut->GetArgs();
        $limit= $pagination->size;
        $offset = $pagination->page * $pagination->size;

        $data = DB::table($table)->whereRaw($sql,$args)->limit($limit)->offset($offset)->get();
        $count = DB::table($table)->whereRaw($sql,$args)->limit($limit)->offset($offset)->count();

        $totalPages = ceil($count / $pagination->size);
        return PaginatedCollection::Init()
            ->withTotalRecords($count)
            ->withCurrentPage($pagination->page)
            ->withCurrentSize($pagination->size)
            ->withData($data->map(function($val){
                $new = clone $this->con;
                return  $new->fill((array)$val);
            }))
            ->withPagesRemaining($totalPages)
            ->withLastPage($totalPages - 1)
            ->withNextPage($pagination->page + 1);
    }

    function FindAllPaginated(Pagination $pagination): PaginatedCollection
    {

        DB::enableQueryLog();
        $table = $this->con->getTable();
        $limit= $pagination->size;
        $offset = $pagination->page * $pagination->size;

        $data = DB::table($table)->select("*")->limit($limit)->offset($offset)->get();

        $count = DB::table($table)->count();

        $totalPages = ceil($count / $pagination->size);
        return PaginatedCollection::Init()
            ->withTotalRecords($count)
            ->withCurrentPage($pagination->page)
            ->withCurrentSize($pagination->size)
            ->withData($data->map(function($val){
                $new = clone $this->con;
                return  $new->fill((array)$val);
            }))
            ->withLastPage($totalPages - 1)
            ->withPagesRemaining($totalPages)
            ->withNextPage($pagination->page + 1);
    }

    function OnCreateOneOrMany($handler): void
    {
        $this->on(EventsRepository::CREATED_EV,$handler);
    }

    function OnDeleteOneOrMany($handler): void
    {
        $this->on(EventsRepository::DELETED_EV,$handler);
    }

    function OnUpdateOneOrMany($handler): void
    {
        $this->on(EventsRepository::UPDATED_EV,$handler);
    }
}

?>
