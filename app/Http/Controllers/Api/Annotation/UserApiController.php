<?php


/**
 *
 * @OA\Post(
 *      path="/api/v1/storeUser",
 *      operationId="storeUser",
 *      tags={"User"},
 *      description="Saving the user during registration. Registration form.",
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
 *          name="phone",
 *          description="User phone.",
 *          example="380991234567",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="User email.",
 *          example="example@email.com",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              maxLength=254,
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="password",
 *          description="User password. Minimum password length 6 symbol.",
 *          example="Password123",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              minLength=6,
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="password_confirmation",
 *          description="Password confirmation.",
 *          example="Password123",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              minLength=6,
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="is_agree",
 *          description="Consent to use the service.",
 *          example=true,
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="boolean"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="Store user.",
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
 *                              @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="phone", type="string", example="380991234567"),
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
 *                            @OA\Items(type="string", example="User with email example@email.com is already registered.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="User with phone +380991234567 is already registered.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="Role not found.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="User is not registered.")
 *                          ),
 *                       ),
 *                      @OA\Schema(type="object",
 *                          @OA\Property(property="non_field_error", type="array",
 *                            @OA\Items(type="string", example="These credentials do not match our records.")
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
 *      path="/api/v1/sendEmailVerificationNotification",
 *      operationId="sendEmailVerificationNotification",
 *      tags={"User"},
 *      description="Sending an email with a url to the user.",
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
 *       @OA\Response(
 *          response=200,
 *          description="Successfully send email.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="mail_send")
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 *
 * @OA\Post(
 *      path="/api/v1/checkEmailVerificationCode",
 *      operationId="checkEmailVerificationCode",
 *      tags={"User"},
 *      description="Verification of the code for verification of email.",
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
 *       @OA\Parameter(
 *           name="locale",
 *           description="locale.",
 *           example="en",
 *           required=false,
 *           in="query",
 *           @OA\Schema(
 *               type="string"
 *           )
 *       ),
 *      @OA\Parameter(
 *          name="code",
 *          description="Code.",
 *          example="123456",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *               type="string",
 *               maxLength=15,
 *           )
 *       ),
 *
 *       @OA\Response(
 *          response=200,
 *          description="Email was verifications.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                      @OA\Property(property="message", type="string", example="email_was_verifications")
 *                     ),
 *                  ),
 *              ),
 *          ),
 *       ),
 * )
 *
 */

/**
 * @OA\Get(
 *      path="/api/v1/getProfile",
 *      operationId="getProfile",
 *      tags={"User"},
 *      description="get User Info",
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
 *          description="get profile info.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                     @OA\Property(property="user", type="array",
 *                          @OA\Items(type="object",
 *                              @OA\Property(property="name", type="string", example="No Name"),
 *                              @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                              @OA\Property(property="description", type="string", example=""),
 *                              @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="phone", type="string", example="+380991234567"),
 *                              @OA\Property(property="email", type="string", example="test@mail.com"),
 *                              @OA\Property(property="avatar", type="string", example="http://advon.me/storage/users/default.png"),
 *                              @OA\Property(property="country", type="string", example=""),
 *                              @OA\Property(property="region", type="string", example=""),
 *                              @OA\Property(property="city", type="string", example=""),
 *                              @OA\Property(property="additional_photos", type="array",
 *                               @OA\Items(type="object",
 *                                   @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                   @OA\Property(property="type", type="string", example="jpg"),
 *                                  ),
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
 *                      ),
 *                  ),
 *              ),
 *
 *          ),
 *       ),
 *)
 */
/**
 * @OA\Put(
 *      path="/api/v1/editProfile",
 *      operationId="editProfile",
 *      tags={"User"},
 *      description="Edit User Info",
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
 *          name="name",
 *          description="name.",
 *          example="username",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="description",
 *          description="desc.",
 *          example="desc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="email",
 *          description="email.",
 *          example="user@gmail.com",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="phone",
 *          description="phone",
 *          example="380976961719",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="latitude",
 *          description="latitude",
 *          example="41.40338",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="longitude",
 *          description="longitude",
 *          example="41.40338",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="country",
 *          description="country",
 *          example="UA",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="region",
 *          description="region",
 *          example="12",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="city",
 *          description="city",
 *          example="112785",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *       @OA\Response(
 *          response=200,
 *          description="get profile info.",
 *          @OA\JsonContent(type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(type="object",
 *                     @OA\Property(property="user", type="array",
 *                          @OA\Items(type="object",
 *                              @OA\Property(property="name", type="string", example="No Name"),
 *                              @OA\Property(property="created_at", type="string", example="19.04.2021"),
 *                              @OA\Property(property="description", type="string", example=""),
 *                              @OA\Property(property="latitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="longitude", type="number", nullable=true, example=null),
 *                              @OA\Property(property="phone", type="string", example="+380991234567"),
 *                              @OA\Property(property="email", type="string", example="test@mail.com"),
 *                              @OA\Property(property="avatar", type="string", example="http://advon.me/storage/users/default.png"),
 *                              @OA\Property(property="country", type="string", example=""),
 *                              @OA\Property(property="region", type="string", example=""),
 *                              @OA\Property(property="city", type="string", example=""),
 *                              @OA\Property(property="additional_photos", type="array",
 *                                  @OA\Items(type="object",
 *                                   @OA\Property(property="photo_url", type="string", example="http://advon.me/storage/users/default.png"),
 *                                   @OA\Property(property="type", type="string", example="jpg"),
 *                                  ),
 *                              ),
 *                              @OA\Property(property="contacts", type="array",
 *                                  @OA\Items(type="object",
     *                                   @OA\Property(property="name", type="string", example="telegram"),
     *                                   @OA\Property(property="value", type="string", example="+380976961719"),
     *                                   @OA\Property(property="created_at", type="string", example="19.04.2021"),
     *                                   @OA\Property(property="updated_at", type="string", example="19.04.2021"),
 *                                 ),
 *                              ),
 *                           ),
 *                      ),
 *                  ),
 *              ),
 *
 *          ),
 *       ),
 * )
 */
/**
 * @OA\Post(
 *      path="/api/v1/uploadProfileAdditionalPhotos",
 *      operationId="uploadProfileAdditionalPhotos",
 *      tags={"User"},
 *      description="Upload additional profile photos",
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
 * )
 */
/**
 * @OA\Post(
 *      path="/api/v1/uploadProfileAvatar",
 *      operationId="uploadProfileAvatar",
 *      tags={"User"},
 *      description="Upload Profile Avatar",
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
 *      path="/api/v1/deleteProfileAvatar",
 *      operationId="deleteProfileAvatar",
 *      tags={"User"},
 *      description="Delete Profile Avatar",
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
 *      path="/api/v1/deleteProfileAdditionalPhoto",
 *      operationId="deleteProfileAdditionalPhoto",
 *      tags={"User"},
 *      description="Delete Profile Additional Photo",
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
