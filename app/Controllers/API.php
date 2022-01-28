<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

class API extends BaseController
{
    /**
     * Get all Clients
     * @return ResponseInterface
     */
    public function users()
    {
        $model = new User();
        return $this->getResponse(
          [
              'message' => 'All Users retrieved successfully',
              'users'   => $model->findAll()
          ]
        );
    }
}