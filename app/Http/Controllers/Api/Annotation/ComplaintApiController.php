<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/storeAdvertisementComplaint",
 *      operationId="storeAdvertisementComplaint",
 *      tags={"Complaint"},
 *      description="Store advertisement complaint.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         description="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hZHZvbmJhY2tcL2FwaVwvdjFcL3N0b3JlVXNlciIsImlhdCI6MTYxODkyMzYyOSwiZXhwIjoxNjE5MDA3NjI5LCJuYmYiOjE2MTg5MjM2MjksImp0aSI6ImRIYkJTOGVUbFExbTdNc00iLCJzdWIiOjQsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.oz3BsCW2qqtYhQx9dA-4qREBF4qw1WLBjra_fMPEO2Y",
 *         @OA\SecurityScheme(
 *              securityScheme="bearerAuth",
 *              in="header",
 *              name="Authorization",
 *              type="apiKey",
 *              scheme="Bearer",
 *              bearerFormat="JWT",
 *           ),
 *       ),
 *      @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="en",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="advertisement_id",
 *          description="Advertisement id.",
 *          example="150",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *       ),
 *       @OA\Parameter(
 *          name="complaint_type_ids[]",
 *          description="Array of complaint type ids",
 *          example="Some complaint type ids",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="integer")
 *
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Advertisement comment  was added.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="message", type="string", example="store_advertisement_complaint"),
 *              ),
 *          ),
 *       ),
 *
 * )
 *
 */

/**
 *
 * @OA\Get (
 *      path="/api/v1/getComplaintTypeList",
 *      operationId="getComplaintTypeList",
 *      tags={"Complaint"},
 *      description="Get complaint types",
 *      @OA\Parameter(
 *          name="locale",
 *          description="locale.",
 *          example="ru",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Get list.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="complaint_type_id", type="integer", example="1"),
 *                      @OA\Property(property="complaint_type_name", type="string", example="Неверная рубрика"),
 *
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */
