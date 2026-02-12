export const useCurrency = () => {
  const formatPrice = (price: number, currency: string = 'TWD'): string => {
    const locale = currency === 'TWD' ? 'zh-TW' : 'en-US';
    
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: currency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(price);
  };

  const formatNumber = (num: number): string => {
    return num.toLocaleString('zh-TW');
  };

  const calculateDiscount = (originalPrice: number, discountPercent: number): number => {
    return originalPrice * (1 - discountPercent / 100);
  };

  const calculateDiscountAmount = (originalPrice: number, discountPercent: number): number => {
    return originalPrice - calculateDiscount(originalPrice, discountPercent);
  };

  return {
    formatPrice,
    formatNumber,
    calculateDiscount,
    calculateDiscountAmount,
  };
};
