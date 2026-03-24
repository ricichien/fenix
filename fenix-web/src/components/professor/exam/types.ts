export interface ExamOption {
  id?: number
  text: string
  is_correct: boolean
}

export interface ExamQuestion {
  id?: number
  statement: string
  options: ExamOption[]
}

export interface ExamPayload {
  id?: number | string
  title: string
  description: string
  questions: ExamQuestion[]
}
