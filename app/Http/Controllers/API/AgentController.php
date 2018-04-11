<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use League\Fractal;
use Swagger\Annotations as SWG;

class AgentController extends ApiController
{
    /**
     * @SWG\Post(
     *     path="/agents",
     *     tags={"Agents"},
     *     summary="Creates a new Agent record",
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Agent object that needs to be created",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the inserted Agent object in the response body and its URL in the Location header",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agent = new \App\Entities\Agent();
        $agent->setAgentType($this->getValue($request->input('agentType'), 
                'Agent', true));
        $agent->setEmail($request->input('email'));
        $agent->setFirstName($request->input('firstName'));
        $agent->setInitials($request->input('initials'));
        $agent->setIpni($request->input('ipni'));
        $agent->setLastName($request->input('lastName'));
        $agent->setLegalName($request->input('legalName'));
        $agent->setName($request->input('name'));
        $this->em->persist($agent);
        $this->em->flush();
        $resource = new Fractal\Resource\Item($agent, 
                new \App\Transformers\AgentTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data, 201)->header('Location', 
                $request->fullUrl() . '/' . $agent->getGuid());
    }

    /**
     * @SWG\Get(
     *     path="/agents/{agent}",
     *     tags={"Agents"},
     *     summary="Gets an Agent resource",
     *     @SWG\Parameter(
     *       in="path",
     *       name="agent",
     *       type="string",
     *       required=true,
     *       description="Identifier (UUID) of the Image"
     *     ),
     *     @SWG\Response(
     *       response="200",
     *       @SWG\Schema(
     *           ref="#/definitions/Agent"
     *       ),
     *       description="Successful response"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid request"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found"
     *     )
     * )
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent = $this->getAgent($id);
        $resource = new Fractal\Resource\Item($agent, 
                new \App\Transformers\AgentTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Put(
     *     path="/agents/{agent}",
     *     tags={"Agents"},
     *     summary="Updates an existing Agent record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="agent",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Agent"
     *     ),
     *     @SWG\Parameter(
     *         in="body",
     *         name="body",
     *         required=true,
     *         description="Agent object that needs to be updated",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response returns the updated Agent object",
     *         @SWG\Schema(
     *             ref="#/definitions/Agent"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $agent = $this->getAgent($id);
        $agent->setAgentType($this->getValue($request->input('agentType', 
                'AgentType', true)));
        $agent->setEmail($request->input('email'));
        $agent->setFirstName($request->input('firstName'));
        $agent->setInitials($request->input('initials'));
        $agent->setIpni($request->input('ipni'));
        $agent->setLastName($request->input('lastName'));
        $agent->setLegalName($request->input('legalName'));
        $agent->setName($request->input('name'));
        $this->em->flush();
        $resource = new Fractal\Resource\Item($agent, 
                new \App\Transformers\AgentTransformer);
        $data = $this->fractal->createData($resource)->toArray();
        return response()->json($data);
    }

    /**
     * @SWG\Delete(
     *     path="/agents/{agent}",
     *     tags={"Agents"},
     *     summary="Deletes an Agent record",
     *     @SWG\Parameter(
     *         in="path",
     *         name="agent",
     *         type="string",
     *         required=true,
     *         description="Identifier (UUID) of the Agent"
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="Successful delete returns empty response body"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid input"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="The requested resource could not be found."
     *     ),
     *     security={
     *         {
     *             "hortflora_auth": {}
     *         }
     *     }
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agent = $this->getAgent($id);
        $this->em->remove($agent);
        return response()->json($data, 204);
    }
    
    protected function getAgent($id): \App\Entities\Agent
    {
        $agent = $this->em->getRepository('\App\Entities\Agent')
                ->findOneBy(['guid' => $id]);
        if (!$agent) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
        return $agent;
    }
}
