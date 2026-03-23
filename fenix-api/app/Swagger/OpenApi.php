<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Fenix API",
 *         version="1.0.0",
 *         description="Documentação da API de exames, questões, tentativas e dashboard."
 *     ),
 *     @OA\Server(
 *         url="/api",
 *         description="API local"
 *     )
 * )
 *
 * @OA\Tag(name="Exams", description="Operações relacionadas a provas")
 * @OA\Tag(name="Questions", description="Operações relacionadas a questões")
 * @OA\Tag(name="Attempts", description="Envio e correção de tentativas")
 * @OA\Tag(name="Dashboard", description="Indicadores e estatísticas")
 *
 * @OA\Schema(
 *     schema="OptionPublic",
 *     type="object",
 *     required={"id","question_id","text"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="question_id", type="integer", example=10),
 *     @OA\Property(property="text", type="string", example="Alternativa A")
 * )
 *
 * @OA\Schema(
 *     schema="OptionWithCorrect",
 *     type="object",
 *     required={"id","question_id","text","is_correct"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="question_id", type="integer", example=10),
 *     @OA\Property(property="text", type="string", example="Alternativa A"),
 *     @OA\Property(property="is_correct", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="QuestionPublic",
 *     type="object",
 *     required={"id","exam_id","statement","options"},
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="exam_id", type="integer", example=1),
 *     @OA\Property(property="statement", type="string", example="Quanto é 2 + 2?"),
 *     @OA\Property(
 *         property="options",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OptionPublic")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="QuestionWithAnswers",
 *     type="object",
 *     required={"id","exam_id","statement","options"},
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="exam_id", type="integer", example=1),
 *     @OA\Property(property="statement", type="string", example="Quanto é 2 + 2?"),
 *     @OA\Property(
 *         property="options",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OptionWithCorrect")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ExamPublic",
 *     type="object",
 *     required={"id","title","created_by","questions","has_attempt","last_result"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Exame de Matemática"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="created_by", type="integer", example=1),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/QuestionPublic")
 *     ),
 *     @OA\Property(property="has_attempt", type="boolean", example=false),
 *     @OA\Property(
 *         property="last_result",
 *         nullable=true,
 *         ref="#/components/schemas/AttemptResult"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ExamWithAnswers",
 *     type="object",
 *     required={"id","title","created_by","questions"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Exame de Matemática"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="created_by", type="integer", example=1),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/QuestionWithAnswers")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="QuestionCreateRequest",
 *     type="object",
 *     required={"exam_id","statement","options"},
 *     @OA\Property(property="exam_id", type="integer", example=1),
 *     @OA\Property(property="statement", type="string", example="Quanto é 2 + 2?"),
 *     @OA\Property(
 *         property="options",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"text","is_correct"},
 *             @OA\Property(property="text", type="string"),
 *             @OA\Property(property="is_correct", type="boolean")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ExamWriteRequest",
 *     type="object",
 *     required={"title","questions"},
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"statement","options"},
 *             @OA\Property(property="statement", type="string"),
 *             @OA\Property(
 *                 property="options",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     required={"text","is_correct"},
 *                     @OA\Property(property="text", type="string"),
 *                     @OA\Property(property="is_correct", type="boolean")
 *                 )
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AttemptAnswerInput",
 *     type="object",
 *     required={"question_id","selected_option_id"},
 *     @OA\Property(property="question_id", type="integer"),
 *     @OA\Property(property="selected_option_id", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="AttemptSubmitRequest",
 *     type="object",
 *     required={"student_id","answers"},
 *     @OA\Property(property="student_id", type="integer"),
 *     @OA\Property(
 *         property="answers",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AttemptAnswerInput")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AttemptDetail",
 *     type="object",
 *     @OA\Property(property="question_id", type="integer"),
 *     @OA\Property(property="question_statement", type="string"),
 *     @OA\Property(property="selected_option_id", type="integer", nullable=true),
 *     @OA\Property(property="selected_text", type="string", nullable=true),
 *     @OA\Property(property="correct_option_id", type="integer", nullable=true),
 *     @OA\Property(property="correct_text", type="string", nullable=true),
 *     @OA\Property(property="is_correct", type="boolean")
 * )
 *
 * @OA\Schema(
 *     schema="AttemptResult",
 *     type="object",
 *     @OA\Property(property="attempt_id", type="integer"),
 *     @OA\Property(property="exam_id", type="integer"),
 *     @OA\Property(property="student_id", type="integer"),
 *     @OA\Property(property="score", type="integer"),
 *     @OA\Property(property="percentage", type="integer"),
 *     @OA\Property(property="total_questions", type="integer"),
 *     @OA\Property(property="correct_count", type="integer"),
 *     @OA\Property(property="wrong_count", type="integer"),
 *     @OA\Property(
 *         property="details",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AttemptDetail")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="GenericMessageResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string")
 * )
 *
 * @OA\Schema(
 *     schema="ExamListItem",
 *     type="object",
 *     required={"id","title","created_by","questions","has_attempt"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="created_by", type="integer"),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/QuestionPublic")
 *     ),
 *     @OA\Property(property="has_attempt", type="boolean")
 * )
 */
class OpenApi {}