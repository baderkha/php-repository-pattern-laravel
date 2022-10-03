<?php

namespace App\Repository;

use Library\Store\Repository\BaseRepository;
use Library\Store\Repository\EventsRepository;
use Library\Store\Repository\SimpleFilterReadOnlyRepository;

interface UserRepository extends BaseRepository,EventsRepository,SimpleFilterReadOnlyRepository
{

}
