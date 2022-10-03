<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;
use Library\Query\FilterExpression\SimpleFilterExpression;
use Library\Query\Pagination\Pagination;

/**
 * Controller that relates to all users
 */
class UserController extends Controller
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
    public function getAllPaginated(Request $ctx)
    {
        $page  = $ctx->get("page","");
        $size = $ctx->get("size","");
        $filter = $ctx->get("filter","");
        $pagination = Pagination::FromUserInput($page,$size);
        $filter = SimpleFilterExpression::FromQuery($filter,new User());

        if ($filter->HasError())
        {
            return \response()->json([
                "data"=>null,
                "err"=> $filter->OutputError()
            ],400);
        }

        return \response()->json($this->repo->FindAllPaginatedAndFilter($pagination,$filter->GetFilter()));
    }


}
