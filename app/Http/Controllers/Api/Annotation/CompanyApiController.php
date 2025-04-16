<?php

/**
 *
 * @OA\Post(
 *      path="/api/v1/storeCompany",
 *      operationId="storeCompany",
 *      tags={"Company"},
 *      description="Create the compnay in profile",
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
 *          name="name",
 *          description="Company Name",
 *          example="TEST NAME",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Info about company",
 *          example="Test description",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=500,
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="company email",
 *          example="email@test.com",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="latitude",
 *          description="",
 *          example="12321.23",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="float",
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="longitude",
 *          description="",
 *          example="12321.23",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="float"
 *          )
 *      ),
 *   @OA\Parameter(
 *          name="site_url",
 *          description="site url",
 *          example="www.google.com",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="video_url",
 *          description="video url",
 *          example="www.google.com/video.mp4",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="audio",
 *          description="audio file",
 *          example="file.mp3",
 *          required=false,
 *          in="query",
 *      ),
 *      @OA\Parameter(
 *          name="document",
 *          description="file",
 *          example="file.wordx",
 *          required=false,
 *          in="query",
 *      ),
 *      @OA\Parameter(
 *          name="hashtags",
 *          description="hashtags",
 *          example="one,two,three,four",
 *          required=false,
 *          in="query",
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Store Company.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                              @OA\Property(property="id", type="int", example="1"),
 *                              @OA\Property(property="name", type="string", example="No Name"),
 *                              @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                              @OA\Property(property="description", type="string", example=""),
 *                              @OA\Property(property="country", type="string", example="UA"),
 *                              @OA\Property(property="region", type="string", example="12"),
 *                              @OA\Property(property="city", type="string", example="3405510"),
 *                              @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="phone", type="string", example="380991234567"),
 *                              @OA\Property(property="email", type="string", example="test@mail.com"),
 *                              @OA\Property(property="hashtags", type="string", example="one,two,thre"),
 *                              @OA\Property(property="additional_photos", type="array",
 *                                  @OA\Items(type="object",
 *                                      @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                      @OA\Property(property="type", type="string", example="jpg"),
 *                                  ),
 *                              ),
 *                              @OA\Property(property="photo", type="object",
 *                                   @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                   @OA\Property(property="type", type="string", example="jpg"),
 *                              ),
 *              ),
 *          ),
 *       ),
 *       @OA\Response(response=400, description="Bad Request",
 *          @OA\JsonContent( type="object",
 *                   anyOf = {
 *                       @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="Company with email example@email.com is already registered.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="Company alreadt exist")
 *                          ),
 *                       ),
 *
 *                  },
 *          ),
 *
 *       ),
 *       @OA\Response(response=404, description="Not Found"),
 *       @OA\Response(response=422, description="Error: HTTP_UNPROCESSABLE_ENTITY. Validation errors",
 *          @OA\JsonContent( type="object",
 *              @OA\Property(property="name", type="array",
 *                  @OA\Items(type="string", example="TThe name field is required.")
 *              ),
 *          ),
 *       ),
 *
 * ),
 *
 *
 * @OA\Get(
 *      path="/api/v1/getCompany",
 *      operationId="getCompany",
 *      tags={"Company"},
 *      description="get Company Info",
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
 *       @OA\Response(
 *          response=200,
 *          description="get company info.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                              @OA\Property(property="id", type="int", example="1"),
 *                              @OA\Property(property="name", type="string", example="No Name"),
 *                              @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                              @OA\Property(property="description", type="string", example=""),
 *                              @OA\Property(property="country", type="string", example="UA"),
 *                              @OA\Property(property="region", type="string", example="12"),
 *                              @OA\Property(property="city", type="string", example="3405510"),
 *                              @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="phone", type="string", example="380991234567"),
 *                              @OA\Property(property="email", type="string", example="test@mail.com"),
 *                              @OA\Property(property="hashtags", type="string", example="one,two,thre"),
 *                              @OA\Property(property="additional_photos", type="array",
 *                                  @OA\Items(type="object",
 *                                      @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                      @OA\Property(property="type", type="string", example="jpg"),
 *                                  ),
 *                              ),
 *                              @OA\Property(property="photo", type="object",
 *                                   @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                   @OA\Property(property="type", type="string", example="jpg"),
 *                              ),
 *                              @OA\Property(property="contacts", type="array",
 *                               @OA\Items(type="object",
 *                                   @OA\Property(property="name", type="string", example="telegram"),
 *                                   @OA\Property(property="value", type="string", example="+380976961719"),
 *                                   @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                                   @OA\Property(property="updated_at", type="string", example="19.04.2021"),
 *                                  ),
 *                              ),
 *                           ),
 *          ),
 *       ),
 *),
 *
 * @OA\Put(
 *      path="/api/v1/editCompany",
 *      operationId="editCompany",
 *      tags={"Company"},
 *      description="Edit Company Info",
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
 *          name="name",
 *          description="Company Name",
 *          example="TEST NAME",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Info about company",
 *          example="Test description",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=500,
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="company email",
 *          example="email@test.com",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="latitude",
 *          description="",
 *          example="12321.23",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="float",
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="longitude",
 *          description="",
 *          example="12321.23",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="float"
 *          )
 *      ),
 *   @OA\Parameter(
 *          name="site_url",
 *          description="site url",
 *          example="www.google.com",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="video_url",
 *          description="video url",
 *          example="www.google.com/video.mp4",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="audio",
 *          description="audio file",
 *          example="file.mp3",
 *          required=false,
 *          in="query",
 *      ),
 *      @OA\Parameter(
 *          name="document",
 *          description="file",
 *          example="file.wordx",
 *          required=false,
 *          in="query",
 *      ),
 *      @OA\Parameter(
 *          name="hashtags",
 *          description="hashtags",
 *          example="one,two,three,four",
 *          required=false,
 *          in="query",
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="get company info.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                              @OA\Property(property="id", type="int", example="1"),
 *                              @OA\Property(property="name", type="string", example="No Name"),
 *                              @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                              @OA\Property(property="description", type="string", example=""),
 *                              @OA\Property(property="country", type="string", example="UA"),
 *                              @OA\Property(property="region", type="string", example="12"),
 *                              @OA\Property(property="city", type="string", example="3405510"),
 *                              @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="phone", type="string", example="380991234567"),
 *                              @OA\Property(property="email", type="string", example="test@mail.com"),
 *                              @OA\Property(property="hashtags", type="string", example="one,two,thre"),
 *                              @OA\Property(property="additional_photos", type="array",
 *                                  @OA\Items(type="object",
 *                                      @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                      @OA\Property(property="type", type="string", example="jpg"),
 *                                  ),
 *                              ),
 *                              @OA\Property(property="photo", type="object",
 *                                   @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                   @OA\Property(property="type", type="string", example="jpg"),
 *                              ),
 *                              @OA\Property(property="contacts", type="array",
 *                               @OA\Items(type="object",
 *                                   @OA\Property(property="name", type="string", example="telegram"),
 *                                   @OA\Property(property="value", type="string", example="+380976961719"),
 *                                   @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                                   @OA\Property(property="updated_at", type="string", example="19.04.2021"),
 *                                  ),
 *                              ),
 *                           ),
 *          ),
 *       ),
 *)
 *
 * @OA\Post(
 *      path="/api/v1/uploadCompanyAdditionalPhotos",
 *      operationId="uploadCompanyAdditionalPhotos",
 *      tags={"Company"},
 *      description="Upload additional Comapy photos",
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
 *          name="images[]",
 *          description="Array of images",
 *          example="Some iamges",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="image")
 *
 *          )
 *
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Uploaded succesful",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                   @OA\Property(property="message", type="string", example="OK"),
 *                  ),
 *              ),
 *
 *          ),
 *       ),
 * @OA\Response(
 *          response=400,
 *          description="non_field_error",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="non_field_error", type="object",example="Some message"),
 *          ),
 *     ),
 * ),
 */
/**
 * @OA\Post(
 *      path="/api/v1/uploadCompanyMainPhoto",
 *      operationId="uploadCompanyMainPhoto",
 *      tags={"Company"},
 *      description="Upload Company Avatar",
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
 *       @OA\Response(
 *          response=200,
 *          description="Uploaded succesful",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                   @OA\Property(property="message", type="string", example="OK"),
 *              ),
 *          ),
 *
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="unuthorizted",
 *     ),
 *      @OA\Response(
 *          response=400,
 *          description="non_field_error",
 *         @OA\JsonContent(type="object",
 *              @OA\Property(property="non_field_error", type="object",example="Some message"),
 *     ),
 * ),
 * )
 */
/**
 * @OA\Post(
 *      path="/api/v1/deleteCompanyMainPhoto",
 *      operationId="deleteCompanyMainPhoto",
 *      tags={"Company"},
 *      description="Delete Company Avatar",
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
 *       @OA\Response(
 *          response=200,
 *          description="Deleted succesful",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                   @OA\Property(property="message", type="string", example="OK"),
 *              ),
 *          ),
 *
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="non_field_error",
 *         @OA\JsonContent(type="object",
 *              @OA\Property(property="non_field_error", type="object",example="Some message"),
 *          ),
 *     ),
 *
 * )
 */
/**
 * @OA\Post(
 *      path="/api/v1/deleteCompanyAdditionalPhoto",
 *      operationId="deleteCompanyAdditionalPhoto",
 *      tags={"Company"},
 *      description="Delete Company Additional Photo",
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
 *          name="id",
 *          description="id of addtionak image",
 *          example="1231",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="int"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Deleted succesful",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                   @OA\Property(property="message", type="string", example="OK"),
 *              ),
 *          ),
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="non_field_error",
 *         @OA\JsonContent(type="object",
 *              @OA\Property(property="non_field_error", type="object",example="Some message"),
 *     ),
 *     ),
 *
 * )
 */
/**
 * @OA\Delete(
 *      path="/api/v1/deleteCompany",
 *      operationId="deleteCompany",
 *      tags={"Company"},
 *      description="Delete Company",
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
 *       @OA\Response(
 *          response=200,
 *          description="Deleted succesful",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="object",
 *                   @OA\Property(property="message", type="string", example="OK"),
 *              ),
 *          ),
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="non_field_error",
 *         @OA\JsonContent(type="object",
 *              @OA\Property(property="non_field_error", type="object",example="Some message"),
 *     ),
 *     ),
 *
 * )
 */
