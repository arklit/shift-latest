<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\DebugNotificationHelper;
use App\Helpers\LoggerHelper;
use App\Mail\ManagerMailService;
use App\Traits\CommonResponsesTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FormsController extends Controller
{
    protected array $params;
    protected array $validation;
    use CommonResponsesTrait;

    /**
     * Метод отправки стандартных форм
     * @param Request $request
     * @param string $code
     * @return JsonResponse
     */
    public function sendForm(Request $request, string $code): JsonResponse
    {
        $this->prepare($code);
        $validator = Validator::make($request->all(), $this->validation['rules'], $this->validation['messages']);

        if ($validator->fails()) {
            return $this->responseFail(
                collect($validator->errors()->messages())->map(fn($error, $name) => $error[0])->toArray()
            );
        }

        try {
            $payload = $validator->validated();
            $mailer = new ManagerMailService();
            $method = $this->params['send']['method'];
            if (!empty($request->allFiles())) {
                $mailer->$method($payload, $request->allFiles(), $this->params['send']['subject'], $this->params['send']['view'], $this->params['send']['key']);
            } else {
                $mailer->$method($payload, $this->params['send']['subject'], $this->params['send']['view'], $this->params['send']['key']);
            }
        } catch (Exception $e) {
            LoggerHelper::debug(json_encode($payload, JSON_UNESCAPED_UNICODE));
            LoggerHelper::commonErrorVerbose($e);
            DebugNotificationHelper::sendVerboseErrorEmail($e);
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->responseOk();
    }

    protected function prepare(string $code): void
    {
        $this->params = CommonHelper::getPreset('forms.' . $code);
        $this->validation = (new CommonHelper)->extractValidationRulesAndMessages($this->params);
        abort_if(!$this->params, 404);
    }
}
