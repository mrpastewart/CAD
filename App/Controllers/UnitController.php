<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Unit;
use App\Models\Shift;

Class UnitController
{
    protected $container;

    /**
     * constructor receives container instance
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function update($request, $response, $args)
    {
        $unit = Unit::find($args['id']);
        if (!$unit) {
            return $response->withStatus(404);
        }

        if (
            $unit->shift->status !== Shift::STATUS_ACTIVE
        ) {
            return $response->withStatus(403);
        }

        $params = $request->getParsedBody();

        try {
            $unit->fill($params);
            $unit->save();
        } catch (\Illuminate\Database\Eloquent\MassAssignmentException $e) {
            return $response->withJson(['error' => 'Invalid attributes sent'], 400);
        }

        return $response;
    }
}
