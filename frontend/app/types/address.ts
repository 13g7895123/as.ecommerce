/**
 * 地址相關型別定義
 */

export interface Address {
  id: string
  userId: string
  recipientName: string
  recipientPhone: string
  city: string
  district: string
  address: string
  postalCode: string
  isDefault: boolean
  createdAt: string
  updatedAt: string
}

export interface CreateAddressPayload {
  recipientName: string
  recipientPhone: string
  city: string
  district: string
  address: string
  postalCode: string
  isDefault?: boolean
}

export interface UpdateAddressPayload extends Partial<CreateAddressPayload> {
  id: string
}
