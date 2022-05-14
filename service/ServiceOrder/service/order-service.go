package service

import (
	"log"

	"github.com/mashingan/smapping"
	"github.com/rickyananda1/golang_gin_gorm_JWT/dto"
	"github.com/rickyananda1/golang_gin_gorm_JWT/entity"
	"github.com/rickyananda1/golang_gin_gorm_JWT/repository"
)

type OrderService interface {
	Insert(b dto.OrderCreateDTO) entity.Order
	Update(b dto.OrderUpdateDTO) entity.Order
	Delete(b entity.Order)
	All() []entity.Order
	FIndById(orderID uint64) entity.Order
}

type orderService struct {
	orderRepository repository.OrderRepository
}

func NewOrderService(orderRepo repository.OrderRepository) OrderService {
	return &orderService{
		orderRepository: orderRepo,
	}
}

func (service *orderService) Insert(b dto.OrderCreateDTO) entity.Order {
	order := entity.Order{}
	err := smapping.FillStruct(&order, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v:", err)
	}
	res := service.orderRepository.InsertOrder(order)
	return res
}

func (service *orderService) Update(b dto.OrderUpdateDTO) entity.Order {
	order := entity.Order{}
	err := smapping.FillStruct(&order, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v:", err)
	}
	res := service.orderRepository.UpdateOrder(order)
	return res
}

func (service *orderService) Delete(b entity.Order) {
	service.orderRepository.DeleteOrder(b)
}

func (service *orderService) All() []entity.Order {
	return service.orderRepository.AllOrder()
}

func (service *orderService) FIndById(orderID uint64) entity.Order {
	return service.orderRepository.FindOrderID(orderID)
}
