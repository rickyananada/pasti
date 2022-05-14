package controller

import (
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/rickyananda1/golang_gin_gorm_JWT/dto"
	"github.com/rickyananda1/golang_gin_gorm_JWT/entity"
	"github.com/rickyananda1/golang_gin_gorm_JWT/helper"
	"github.com/rickyananda1/golang_gin_gorm_JWT/service"
)

type OrderController interface {
	All(context *gin.Context)
	FindByID(context *gin.Context)
	Insert(context *gin.Context)
	Update(context *gin.Context)
	Delete(context *gin.Context)
}

type orderController struct {
	orderService service.OrderService
	jwtService   service.JWTService
}

func NewOrderController(orderServ service.OrderService, jwtServ service.JWTService) OrderController {
	return &orderController{
		orderService: orderServ,
		jwtService:   jwtServ,
	}
}

func (c *orderController) All(context *gin.Context) {
	var orders []entity.Order = c.orderService.All()
	res := helper.BuildResponse(true, "OK", orders)
	context.JSON(http.StatusOK, res)
}

func (c *orderController) FindByID(context *gin.Context) {
	id, err := strconv.ParseUint(context.Param("id"), 0, 0)
	if err != nil {
		res := helper.BuildErrorResponse("No param id was found", err.Error(), helper.EmptyObj{})
		context.AbortWithStatusJSON(http.StatusBadRequest, res)
	}
	var order entity.Order = c.orderService.FIndById(id)
	if (order == entity.Order{}) {
		res := helper.BuildErrorResponse("Data not found", "No Data with given id", helper.EmptyObj{})
		context.JSON(http.StatusNotFound, res)
	} else {
		res := helper.BuildResponse(true, "OK", order)
		context.JSON(http.StatusOK, res)
	}
}

func (c *orderController) Insert(context *gin.Context) {
	var orderCreateDTO dto.OrderCreateDTO
	errDTO := context.ShouldBind(&orderCreateDTO)
	if errDTO != nil {
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, res)
	} else {
		result := c.orderService.Insert(orderCreateDTO)
		response := helper.BuildResponse(true, "OK", result)
		context.JSON(http.StatusOK, response)
	}
}

func (c *orderController) Update(context *gin.Context) {
	var book entity.Order
	var orderUpdateDTO dto.OrderUpdateDTO
	errDTO := context.ShouldBind(&orderUpdateDTO)
	id, err := strconv.ParseUint(context.Param("id"), 0, 0)
	book.ID = id
	if errDTO != nil {
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, res)
	}

	if err != nil {
		response := helper.BuildErrorResponse("Failed to get id", "No param id were found", helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, response)
	}

	if orderUpdateDTO.ID == book.ID {
		result := c.orderService.Update(orderUpdateDTO)
		response := helper.BuildResponse(true, "OK", result)
		context.JSON(http.StatusOK, response)
	} else {
		response := helper.BuildErrorResponse("You dont have permission", "You are not the owner", helper.EmptyObj{})
		context.JSON(http.StatusForbidden, response)
	}

}

func (c *orderController) Delete(context *gin.Context) {
	var order entity.Order
	id, err := strconv.ParseUint(context.Param("id"), 0, 0)
	if err != nil {
		response := helper.BuildErrorResponse("Failed to get id", "No param id were found", helper.EmptyObj{})
		context.JSON(http.StatusBadRequest, response)
	}
	order.ID = id
	if order.ID == id {
		c.orderService.Delete(order)
		res := helper.BuildResponse(true, "Delete", helper.EmptyObj{})
		context.JSON(http.StatusOK, res)
	} else {
		response := helper.BuildErrorResponse("You dont have permission", "You are not the owner", helper.EmptyObj{})
		context.JSON(http.StatusForbidden, response)
	}
}
