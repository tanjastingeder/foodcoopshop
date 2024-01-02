<?php
declare(strict_types=1);

namespace Admin\Policy;

use Cake\Http\ServerRequest;
use Authorization\Policy\RequestPolicyInterface;

class CategoriesPolicy implements RequestPolicyInterface
{

    public function canAccess($identity, ServerRequest $request)
    {

        if ($identity === null) {
            return false;
        }

        return $identity->isSuperadmin() || $identity->isAdmin();

    }

}