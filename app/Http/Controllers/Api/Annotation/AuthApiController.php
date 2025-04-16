<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/login",
 *      operationId="login",
 *      tags={"Auth"},
 *      description="User authorization.",
 *     @OA\Parameter(
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
 *          name="login",
 *          description="login.",
 *          example="380991234567 or example@email.com",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          description="User password. Minimum password length 6 symbol.",
 *          example="Password123",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              minLength=6,
 *          )
 *      ), *
 *       @OA\Response(
 *          response=200,
 *          description="User authorization.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="token", type="string"),
 *                      @OA\Property(property="token_time_expired", type="integer", example=1618649534),
 *                      @OA\Property(property="user", type="array",
 *                          @OA\Items(type="object",
 *                              @OA\Property(property="name", type="string", example="No Name"),
 *                              @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                              @OA\Property(property="description", type="string", example=""),
 *                              @OA\Property(property="latitude", type="number", format="float", nullable=true, example=12.121212),
 *                              @OA\Property(property="longitude", type="number", format="float",nullable=true, example=-131.121314),
 *                              @OA\Property(property="phone", type="string", example="+380991234567"),
 *                              @OA\Property(property="email", type="string", example="test@mail.com"),
 *                  ),
 *              ),
 *                  ),
 *              ),
 *          ),
 *       ),
 *       @OA\Response(response=400, description="Bad Request",
 *          @OA\JsonContent( type="object",
 *                   anyOf = {
 *                       @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="These credentials do not match our records.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="The user blocked.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="Role not found.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="The user email address is not verified.")
 *                          ),
 *                       ),
 *                  },
 *          ),
 *
 *       ),
 *       @OA\Response(response=404, description="Not Found"),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="email", type="array",
 *                  @OA\Items(type="string", example="TThe email field is required.")
 *              ),
 *          ),
 *       ),
 *
 * )
 *
 */

/**
 *
 * @OA\Post(
 *      path="/api/v1/refresh",
 *      operationId="userRefresh",
 *      tags={"Auth"},
 *      description="Token refresh.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         description="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9jYXJcL2FwaVwvbG9naW5TZWxsZXIiLCJpYXQiOjE2MTU4MzYzMDksImV4cCI6MTYxNTkyMjcwOSwibmJmIjoxNjE1ODM2MzA5LCJqdGkiOiJxZkNPYmwxUUU2U0lxSldaIiwic3ViIjoxMywicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.BhzZTfcbp5cmFcAjz5b_fw6UpghUdOwrTG_O0ASodkQ",
 *         @OA\SecurityScheme(
 *              securityScheme="bearerAuth",
 *              in="header",
 *              name="Authorization",
 *              type="apiKey",
 *              scheme="Bearer",
 *              bearerFormat="JWT",
 *           ),
 *       ),
 *       @OA\Response(
 *          response=200,
 *          description="Token refresh.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="token", type="string"),
 *                      @OA\Property(property="token_time_expired", type="integer", example=1618649534
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 *       @OA\Response(response=401, description="Unauthorized"),
 *       @OA\Response(response=404, description="Not Found"),
 * )
 *
 */


/**
 *
 * @OA\Post(
 *      path="/api/v1/logout",
 *      operationId="userlogout",
 *      tags={"Auth"},
 *      description="User logout.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         description="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9jYXJcL2FwaVwvbG9naW5TZWxsZXIiLCJpYXQiOjE2MTU4MzYzMDksImV4cCI6MTYxNTkyMjcwOSwibmJmIjoxNjE1ODM2MzA5LCJqdGkiOiJxZkNPYmwxUUU2U0lxSldaIiwic3ViIjoxMywicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.BhzZTfcbp5cmFcAjz5b_fw6UpghUdOwrTG_O0ASodkQ",
 *         @OA\SecurityScheme(
 *              securityScheme="bearerAuth",
 *              in="header",
 *              name="Authorization",
 *              type="apiKey",
 *              scheme="Bearer",
 *              bearerFormat="JWT",
 *           ),
 *       ),
 *       @OA\Response(
 *          response=200,
 *          description="User logout.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="successfully_logged_out")
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 *       @OA\Response(response=401, description="Unauthorized"),
 *       @OA\Response(response=404, description="Not Found"),
 * )
 *
 */
